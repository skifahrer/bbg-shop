<?php

namespace App\ApiResource;

use App\Entity\Order;
use App\Entity\Cart;
use App\Entity\Checkout;
use App\Entity\ItemQuantity;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use App\Repository\CartRepository;
use App\Repository\CheckoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[Get(
    uriTemplate: '/orders/{id}',
    controller: 'App\ApiResource\OrderController::getOrder',
    security: "is_granted('ROLE_USER')",
    name: 'api_get_order'
)]
#[GetCollection(
    uriTemplate: '/orders',
    controller: 'App\ApiResource\OrderController::getOrders',
    security: "is_granted('ROLE_USER')",
    name: 'api_get_orders'
)]
class OrderController extends AbstractController
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function getOrder(#[CurrentUser] User $user, string $id): JsonResponse
    {
        $order = $this->orderRepository->find($id);

        if (!$order || $order->getUser() !== $user) {
            return $this->json(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
                               'order' => [
                                   'id' => $order->getId(),
                                   'status' => $order->getStatus(),
                                   'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
                                   'finalPrice' => $order->getFinalPrice(),
                                   'shippingAddress' => $order->getShippingAddress(),
                                   'invoiceAddress' => $order->getInvoiceAddress(),
                                   'items' => array_map(function($item) {
                                       return [
                                           'id' => $item->getId(),
                                           'product' => [
                                               'id' => $item->getProduct()->getId(),
                                               'name' => $item->getProduct()->getLocalizedTitle('en'),
                                               'price' => $item->getProduct()->getOxprice()
                                           ],
                                           'quantity' => $item->getQuantity()
                                       ];
                                   }, $order->getItemQuantities()->toArray())
                               ]
                           ]);
    }

    public function getOrders(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $orders = $this->orderRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->json([
                               'orders' => array_map(function($order) {
                                   return [
                                       'id' => $order->getId(),
                                       'status' => $order->getStatus(),
                                       'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
                                       'finalPrice' => $order->getFinalPrice(),
                                       'items' => array_map(function($item) {
                                           return [
                                               'id' => $item->getId(),
                                               'product' => [
                                                   'id' => $item->getProduct()->getId(),
                                                   'name' => $item->getProduct()->getLocalizedTitle('en'),
                                                   'price' => $item->getProduct()->getOxprice()
                                               ],
                                               'quantity' => $item->getQuantity()
                                           ];
                                       }, $order->getItemQuantities()->toArray())
                                   ];
                               }, $orders)
                           ]);
    }
}
