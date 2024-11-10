<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LocaleListener
{
    private string $defaultLocale;
    private array $supportedLocales;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(string $defaultLocale, array $supportedLocales, UrlGeneratorInterface $urlGenerator)
    {
        $this->defaultLocale = $defaultLocale;
        $this->supportedLocales = $supportedLocales;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Try to get locale from session
        if ($locale = $request->getSession()->get('_locale')) {
            if (in_array($locale, $this->supportedLocales)) {
                $request->setLocale($locale);

                return;
            }
        }

        // Try to get locale from browser
        $browserLocale = $request->getPreferredLanguage($this->supportedLocales);
        if ($browserLocale) {
            $request->setLocale($browserLocale);
            $request->getSession()->set('_locale', $browserLocale);

            return;
        }

        // Fallback to default locale
        $request->setLocale($this->defaultLocale);
        $request->getSession()->set('_locale', $this->defaultLocale);
    }
}
