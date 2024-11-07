<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('base.html.twig', [
            'carousel' => true,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route('/registration', name: 'app_registration')]
    public function registration(): Response
    {
        return $this->render('registration.html.twig');
    }
}
