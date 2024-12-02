<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude([
        'vendor',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        'blank_line_after_opening_tag' => true,
        'declare_strict_types' => true,
    ])
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
