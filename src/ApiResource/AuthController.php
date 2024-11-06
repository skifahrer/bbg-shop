<?php

namespace App\ApiResource;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;


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
        EntityManagerInterface $entityManager
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $userRepository->findOneBy(['email' => $email]);


        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(
                ['error' => 'Invalid credentials.'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = $jwtManager->create($user);
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
