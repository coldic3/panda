<?php

/** @var PhpCsFixer\Config $csFixerConfig */
$csFixerConfig = require __DIR__.'/.php-cs-fixer.dist.php';

$finder = (new PhpCsFixer\Finder())->in(__DIR__.'/tests')->in(__DIR__.'/spec');

$rules = $csFixerConfig->getRules();
$rules['visibility_required'] = ['elements' => ['const', 'property']];
$rules['php_unit_method_casing'] = false;

return $csFixerConfig->setRules($rules)->setFinder($finder);
