<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$root = dirname(__FILE__);
$pint = json_decode(file_get_contents($root.'/pint.json'), associative: true);
$rules = $pint['rules'];

$rules = array_merge($rules, [
    'ErickSkrauch/blank_line_around_class_body' => true,
    "braces_position" => [
        ...$rules['braces_position'],
        "classes_opening_brace" => "same_line",
    ],
]);

$finder = new Finder()
    ->in([__DIR__])
    ->exclude([
        'node_modules',
        'resources',
        'storage',
        'vendor',
    ]);

$config = new Config()
    ->registerCustomFixers(new \ErickSkrauch\PhpCsFixer\Fixers())
    ->setRiskyAllowed(true)
    ->setHideProgress(false)
    ->setCacheFile($root.'/.php-cs-fixer.cache.json')
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules($rules)
    ->setFinder($finder);

return $config;
