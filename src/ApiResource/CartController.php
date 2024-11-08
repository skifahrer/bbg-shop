<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Cart;
use App\Entity\ItemQuantity;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;


#[Get(
    uriTemplate: '/cart',
    controller: 'App\ApiResource\CartController::getCart',
    security: "is_granted('ROLE_USER')",
    name: 'api_get_cart'
)]
#[Post(
    uriTemplate: '/cart/add',
    controller: 'App\ApiResource\CartController::addToCart',
    security: "is_granted('ROLE_USER')",
    name: 'api_add_to_cart'
)]
#[Put(
    uriTemplate: '/cart/update',
    controller: 'App\ApiResource\CartController::updateCart',
    security: "is_granted('ROLE_USER')",
    name: 'api_update_cart'
)]

class CartController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CartRepository $cartRepository,
        private ProductRepository $productRepository
    ) {}

    public function getCart(#[CurrentUser] User $user,  Request $request): JsonResponse
    {
        $locale = $request->query->get('locale', 'en');

        $activeCart = $this->cartRepository->findActiveCartByUser($user);

        if (!$activeCart) {
            $activeCart = new Cart();
            $activeCart->setUser($user);
            $this->entityManager->persist($activeCart);
            $this->entityManager->flush();
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

        return $this->json([
                               'cart_id' => $activeCart->getId(),
                               'items' => $items,
                               'total_amount' => $totalAmount,
                               'items_count' => count($items)
                           ]);
    }

    public function addToCart(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $productId = $data['product_id'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$productId) {
            return $this->json(['error' => 'Product ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $product = $this->productRepository->find($productId);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        if ($product->getStock() < $quantity) {
            return $this->json(['error' => 'Insufficient stock'], Response::HTTP_BAD_REQUEST);
        }

        $activeCart = $this->cartRepository->findActiveCartByUser($user);
        if (!$activeCart) {
            $activeCart = new Cart();
            $activeCart->setUser($user);
            $this->entityManager->persist($activeCart);
        }

        $itemQuantity = null;
        foreach ($activeCart->getItemQuantities() as $iq) {
            if ($iq->getProduct()->getId() === $product->getId()) {
                $itemQuantity = $iq;
                break;
            }
        }

        if ($itemQuantity) {
            $itemQuantity->setQuantity($itemQuantity->getQuantity() + $quantity);
        } else {
            $itemQuantity = new ItemQuantity();
            $itemQuantity->setCart($activeCart);
            $itemQuantity->setProduct($product);
            $itemQuantity->setQuantity($quantity);
            $this->entityManager->persist($itemQuantity);
        }

        $this->entityManager->flush();

        return $this->json([
                               'message' => 'Product added to cart successfully',
                               'cart_id' => $activeCart->getId()
                           ]);
    }

    public function updateCart(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $productId = $data['product_id'] ?? null;
        $quantity = $data['quantity'] ?? 0;

        if (!$productId) {
            return $this->json(['error' => 'Product ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $activeCart = $this->cartRepository->findActiveCartByUser($user);
        if (!$activeCart) {
            return $this->json(['error' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        $itemQuantity = $activeCart->getItemQuantities()->filter(function ($iq) use ($productId) {
            return $iq->getProduct()->getId() == $productId;
        })->first();

        if (!$itemQuantity) {
            return $this->json(['error' => 'Product not found in cart'], Response::HTTP_NOT_FOUND);
        }

        if ($quantity <= 0) {
            $activeCart->removeItemQuantity($itemQuantity);
        } else {
            if ($itemQuantity->getProduct()->getStock() < $quantity) {
                return $this->json(['error' => 'Insufficient stock'], Response::HTTP_BAD_REQUEST);
            }
            $itemQuantity->setQuantity($quantity);
        }

        $this->cartRepository->save($activeCart, true);

        return $this->json(['message' => 'Cart updated successfully']);
    }
}
