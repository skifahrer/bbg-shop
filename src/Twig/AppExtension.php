<?php
namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_parameter', [$this, 'getAppParameter']),
        ];
    }

    public function getAppParameter(string $name)
    {
        return $this->parameterBag->get($name);
    }
}
