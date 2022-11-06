<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'blank_line_between_import_groups' => false,
        'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
;
