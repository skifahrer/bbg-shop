<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Checkout;
use App\Entity\Order;
use App\Entity\User;
use App\Enum\PaymentType;
use App\Repository\CartRepository;
use App\Repository\CheckoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Get(
    uriTemplate: '/checkout',
    controller: 'App\ApiResource\CheckoutController::getCurrentCheckout',
    security: "is_granted('ROLE_USER')",
    name: 'api_get_current_checkout'
)]
#[Post(
    uriTemplate: '/checkout',
    controller: 'App\ApiResource\CheckoutController::createOrUpdateCheckout',
    security: "is_granted('ROLE_USER')",
    name: 'api_create_checkout'
)]
#[Post(
    uriTemplate: '/checkout/{id}/place-order',
    controller: 'App\ApiResource\CheckoutController::placeOrder',
    security: "is_granted('ROLE_USER')",
    name: 'api_place_order'
)]
#[Get(
    uriTemplate: '/checkout/{id}',
    controller: 'App\ApiResource\CheckoutController::getCheckout',
    security: "is_granted('ROLE_USER')",
    name: 'api_get_checkout'
)]
class CheckoutController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CartRepository $cartRepository,
        private CheckoutRepository $checkoutRepository
    ) {}

    public function getCurrentCheckout(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $locale = $request->query->get('locale', 'en');

        // First get the active cart
        $activeCart = $this->cartRepository->findActiveCartByUser($user);

        if (!$activeCart) {
            return $this->json([
                                   'message' => 'No active cart found',
                                   'checkout' => null
                               ]);
        }

        // Get checkout associated with the active cart
        $checkout = $activeCart->getCheckout();

        if (!$checkout) {
            return $this->json([
                                   'message' => 'No checkout process started',
                                   'checkout' => null
                               ]);
        }

        $items = [];
        $totalAmount = 0;

        foreach ($activeCart->getItemQuantities() as $itemQuantity) {
            $product = $itemQuantity->getProduct();
            $amount = $product->getOxprice() * $itemQuantity->getQuantity();
            $totalAmount += $amount;

            $items[] = [
                'id' => $product->getId(),
                'title' => $product->getLocalizedTitle($locale),
                'price' => $product->getOxprice(),
                'quantity' => $itemQuantity->getQuantity(),
                'amount' => $amount
            ];
        }

        $response = [
            'checkout_id' => $checkout->getId(),
            'created_at' => $checkout->getCreatedAt()->format('Y-m-d H:i:s'),
            'shipping_address' => $checkout->getShippingAddress(),
            'invoice_address' => $checkout->getInvoiceAddress(),
            'payment_type' => $checkout->getPaymentType()?->value,
            'items' => $items,
            'total_amount' => $totalAmount,
            'items_count' => count($items)
        ];

        $order = $checkout->getOrder();
        if ($order) {
            $response['order'] = [
                'id' => $order->getId(),
                'status' => $order->getStatus(),
                'created_at' => $order->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }

        return $this->json($response);
    }

    public function createOrUpdateCheckout(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Get active cart
        $activeCart = $this->cartRepository->findActiveCartByUser($user);
        if (!$activeCart || $activeCart->getItemQuantities()->isEmpty()) {
            return $this->json(['error' => 'No active cart found or cart is empty'], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Create or update checkout
            $checkout = $activeCart->getCheckout();
            $isNewCheckout = false;

            if (!$checkout) {
                $checkout = new Checkout();
                $checkout->setUser($user);
                $checkout->setCart($activeCart);
                $checkout->setCreatedAt(new \DateTime());
                $isNewCheckout = true;
            }

            // Update checkout with provided data, if any
            if (empty($data['shipping_address'])) {
                $data['shipping_address']="";
            }
            if (empty($data['invoice_address'])) {
                $data['invoice_address']="";
            }
            if (empty($data['payment_type'])) {
                $data['payment_type']="credit_card";
            }

            $checkout->setShippingAddress($data['shipping_address']);
            $checkout->setInvoiceAddress($data['invoice_address']);
            $checkout->setPaymentType(PaymentType::from($data['payment_type']));
            $this->entityManager->persist($checkout);
            $this->entityManager->flush();

            $response = [
                'checkout_id' => $checkout->getId(),
                'message' => $isNewCheckout ? 'Checkout created successfully' : 'Checkout details updated successfully'
            ];

            return $this->json(
                $response,
                $isNewCheckout ? Response::HTTP_CREATED : Response::HTTP_OK
            );

        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Failed to process checkout: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function placeOrder(#[CurrentUser] User $user, string $id): JsonResponse
    {
        $checkout = $this->checkoutRepository->find($id);

        if (!$checkout) {
            return $this->json(['error' => 'Checkout not found'], Response::HTTP_NOT_FOUND);
        }

        if ($checkout->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        $cart = $checkout->getCart();

        // Verify stock availability for all items
        foreach ($cart->getItemQuantities() as $itemQuantity) {
            $product = $itemQuantity->getProduct();
            if ($product->getStock() < $itemQuantity->getQuantity()) {
                return $this->json([
                                       'error' => sprintf(
                                           'Insufficient stock for product %s. Available: %d, Requested: %d',
                                           $product->getId(),
                                           $product->getStock(),
                                           $itemQuantity->getQuantity()
                                       )
                                   ], Response::HTTP_BAD_REQUEST);
            }
        }

        try {
            // Create new order
            $order = new Order();
            $order->setUser($user);
            $order->setStatus('pending');
            $order->setCreatedAt(new \DateTime());

            // Transfer items from cart to order and update stock
            foreach ($cart->getItemQuantities() as $itemQuantity) {
                $product = $itemQuantity->getProduct();

                // Update product stock
                $product->setStock($product->getStock() - $itemQuantity->getQuantity());

                // Move item to order
                $itemQuantity->setCart(null);
                $itemQuantity->setOrder($order);
                $order->addItemQuantity($itemQuantity);
            }

            $this->entityManager->persist($order);

            // Link order to checkout
            $checkout->setOrder($order);

            $this->entityManager->flush();

            return $this->json([
                                   'order_id' => $order->getId(),
                                   'status' => $order->getStatus(),
                                   'created_at' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
                                   'message' => 'Order placed successfully'
                               ]);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to place order: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCheckout(#[CurrentUser] User $user, string $id): JsonResponse
    {
        $checkout = $this->checkoutRepository->find($id);

        if (!$checkout) {
            return $this->json(['error' => 'Checkout not found'], Response::HTTP_NOT_FOUND);
        }

        // Ensure the checkout belongs to the current user
        if ($checkout->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        $cart = $checkout->getCart();
        $order = $checkout->getOrder();

        $items = [];
        $totalAmount = 0;

        // Get items from either cart or order
        $itemQuantities = $order ? $order->getItemQuantities() : $cart->getItemQuantities();

        foreach ($itemQuantities as $itemQuantity) {
            $product = $itemQuantity->getProduct();
            $amount = $product->getOxprice() * $itemQuantity->getQuantity();
            $totalAmount += $amount;

            $items[] = [
                'id' => $product->getId(),
                'title' => $product->getLocalizedTitle('en'), // You might want to make this configurable
                'price' => $product->getOxprice(),
                'quantity' => $itemQuantity->getQuantity(),
                'amount' => $amount
            ];
        }

        $response = [
            'id' => $checkout->getId(),
            'created_at' => $checkout->getCreatedAt()->format('Y-m-d H:i:s'),
            'shipping_address' => $checkout->getShippingAddress(),
            'invoice_address' => $checkout->getInvoiceAddress(),
            'payment_type' => $checkout->getPaymentType()?->value,
            'items' => $items,
            'total_amount' => $totalAmount
        ];

        if ($order) {
            $response['order'] = [
                'id' => $order->getId(),
                'status' => $order->getStatus(),
                'created_at' => $order->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }

        return $this->json($response);
    }
}
