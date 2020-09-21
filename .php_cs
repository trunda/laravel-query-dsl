<?php
$finder = PhpCsFixer\Finder::create()
    ->notPath('bootstrap/cache')
    ->notPath('storage')
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        'binary_operator_spaces' => ['align_double_arrow' => true],
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'not_operator_with_successor_space' => false,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'yoda_style' => false,
        'concat_space' => ['spacing' => 'one'],
        'method_chaining_indentation' => true,
    ))
    ->setFinder($finder)
;
