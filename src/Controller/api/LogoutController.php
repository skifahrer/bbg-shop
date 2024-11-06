<?php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;  // Updated import
use Doctrine\ORM\EntityManagerInterface;

class LogoutController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
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
