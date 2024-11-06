<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticationSuccessListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {}

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();
        $token = new UsernamePasswordToken(
            $user,
            'api', // your firewall name
            $user->getRoles()
        );

        $this->tokenStorage->setToken($token);
    }
}
