<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
             __DIR__ . '/src',
             __DIR__ . '/tests',
         ])
;

return (new PhpCsFixer\Config())
    ->setRules([
                   '@Symfony' => true,
                   'array_syntax' => ['syntax' => 'short'],
                   'ordered_imports' => true,
                   'no_unused_imports' => true,
               ])
    ->setFinder($finder)
    ;
