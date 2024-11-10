<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Post(
    uriTemplate: '/auth/login',
    controller: 'App\ApiResource\AuthController::login',
    name: 'api_login'
)]
#[Post(
    uriTemplate: '/auth/logout',
    controller: 'App\ApiResource\AuthController::logout',
    name: 'api_logout'
)]
class AuthController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(
                ['error' => 'Email and password are required.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $userArray = $userRepository->findOneByEmailForAuth($data['email']);

        if (!$userArray) {
            return new JsonResponse(
                ['error' => 'User not exists.'],
                Response::HTTP_NOT_FOUND
            );
        }

        if (!($userArray['password'] == $data['password'])) {
            return new JsonResponse(
                ['error' => 'Invalid credentials.'],
                Response::HTTP_UNAUTHORIZED
            );
        }
        // we need to create this entity to generate the token
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        // Set the ID using reflection since it's private
        $reflectionProperty = new \ReflectionProperty(User::class, 'id');
        $reflectionProperty->setValue($user, Uuid::fromString($userArray['id']));

        // Set default roles
        $user->setRoles(['ROLE_USER']);

        $token = $jwtManager->createFromPayload($user, ['exp' => (new \DateTime('+24 hours'))->getTimestamp()]);

        return new JsonResponse(['jwt' => $token]);
    }

    public function logout(): JsonResponse
    {
        $user = $this->security->getUser();
        if ($user) {
            // Clear session data
            $session = $this->get('session');
            $session->clear();
        }

        return new JsonResponse(['message' => 'Logged out successfully']);
    }
}
