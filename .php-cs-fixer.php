<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// https://cs.symfony.com/doc/rules/
// https://cs.symfony.com/doc/ruleSets/
$rules = [
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    '@Symfony' => true,
    'declare_strict_types' => true,

];

$finder = (new Finder())
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->in([__DIR__])
    ->name('*.php')
    ->exclude('templates')
    ->exclude('vendor')
    ->exclude('public');

return (new Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true);
