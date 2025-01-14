<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JWTAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private TokenExtractorInterface $tokenExtractor,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return false !== $this->tokenExtractor->extract($request);
    }

    public function authenticate(Request $request): Passport
    {
        $token = $this->tokenExtractor->extract($request);

        if (false === $token) {
            throw new AuthenticationException('No JWT token found');
        }

        $payload = $this->jwtManager->parse($token);

        // if payload does not contain id
        if (!isset($payload['id'])) {
            throw new AuthenticationException('Invalid JWT token');
        }

        return new SelfValidatingPassport(
            new UserBadge($payload['id'])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Continue with the request
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }
}
