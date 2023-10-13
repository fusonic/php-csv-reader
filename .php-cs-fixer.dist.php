<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('example');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PHP82Migration' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => 'Copyright (c) Fusonic GmbH. All rights reserved.'.\PHP_EOL.'Licensed under the MIT License. '.
                'See LICENSE file in the project root for license information.',
            'location' => 'after_open',
        ],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'php_unit_strict' => true,
        'single_line_throw' => false,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
