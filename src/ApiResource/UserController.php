<?php
namespace App\ApiResource;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;

#[Post(
    uriTemplate: '/users/register',
    controller: 'App\ApiResource\UserController::register',
    name: 'api_register'
)]
#[Get(
    uriTemplate: '/users/me',
    controller: 'App\ApiResource\UserController::me',
    security: "is_granted('ROLE_USER')",
    name: 'api_me'
)]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private ValidatorInterface $validator,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return $this->json(['error' => 'Email and password are required.'], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->json(['error' => 'Email already exists.'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setFamily($data['family']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = $this->jwtManager->createFromPayload($user, ['exp' => (new \DateTime('+24 hours'))->getTimestamp()]);

        return $this->json([
                               'message' => 'User registered successfully',
                               'jwt' => $token
                           ], Response::HTTP_CREATED, [], ['groups' => ['user:read']]);
    }

    public function me(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $locale = $request->query->get('locale', 'en');

        return $this->json([
                               'user' => [
                                   'id' => $user->getId(),
                                   'email' => $user->getEmail(),
                                   'name' => $user->getName(),
                                   'family' => $user->getFamily(),
                                   'roles' => $user->getRoles(),
                                   'shipping_address' => $user->getShippingAddress(),
                                   'carts' => array_map(function($cart) use ($locale) {
                                       return [
                                           'id' => $cart->getId(),
                                           'items' => array_map(function($item) use ($locale) {
                                               return [
                                                   'id' => $item->getId(),
                                                   'product' => [
                                                       'id' => $item->getProduct()->getId(),
                                                       'name' => $item->getProduct()->getLocalizedTitle($locale),
                                                       'price' => $item->getProduct()->getOxprice()
                                                   ],
                                                   'quantity' => $item->getQuantity()
                                               ];
                                           }, $cart->getItemQuantities()->toArray())
                                       ];
                                   }, $user->getCarts()->toArray()),
                                   'orders' => array_map(function($order) use ($locale) {
                                       return [
                                           'id' => $order->getId(),
                                           'status' => $order->getStatus(),
                                           'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
                                           'finalPrice' => $order->getFinalPrice()
                                       ];
                                   }, $user->getOrders()->toArray()),
                                   'checkouts' => array_map(function($checkout) {
                                       return [
                                           'id' => $checkout->getId(),
                                           'cart' => [
                                               'id' => $checkout->getCart()->getId(),
                                           ],
                                           'createdAt' => $checkout->getCreatedAt()->format('Y-m-d H:i:s'),
                                       ];
                                   }, $user->getCheckouts()->toArray())
                               ]
                           ], 200, [], ['groups' => ['user:read']]);
    }
}
