<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => false,
        'blank_line_before_return' => false,
        'cast_spaces' => false,
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => true,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_separation' => false,
        'single_quote' => false,
        'trailing_comma_in_multiline_array' => false
    ])
    //->setIndent("\t")
    ->setFinder($finder);
