<?php

namespace App\Controller\api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private JWTTokenManagerInterface $jwtManager;
    private UserRepository $userRepository;
    private SessionInterface $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): Response
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
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = $this->jwtManager->create($user);

        // Save the JWT token to the session
        $this->session->set('jwt', $token);

        return $this->json([
            'message' => 'User registered successfully',
            'user' => [
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
            'jwt' => $token
        ], Response::HTTP_CREATED);
    }
}
