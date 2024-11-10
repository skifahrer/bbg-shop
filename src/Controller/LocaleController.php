<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/change-locale/{locale}', name: 'change_locale')]
    public function changeLocale(Request $request, string $locale): Response
    {
        // Store the locale in session
        $request->getSession()->set('_locale', $locale);

        // Get the current route name and parameters
        $routeName = $request->get('_route');
        $routeParams = $request->get('_route_params', []);

        // Update the route parameters with the new locale
        $routeParams['_locale'] = $locale;

        // Redirect to the current route with the updated locale
        return $this->redirectToRoute($routeName, $routeParams);
    }
}
