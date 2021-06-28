<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        //'no_superfluous_phpdoc_tags' => false
    ])
    ->setFinder($finder)
;
