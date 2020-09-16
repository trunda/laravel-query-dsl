<?php

require __DIR__ . '/../vendor/autoload.php';

$compiler = Hoa\Compiler\Llk\Llk::load(new Hoa\File\Read(__DIR__ . '/Grammar/Grammar.pp'));

$ast = $compiler->parse($argv[1]);
$dump = new Hoa\Compiler\Visitor\Dump();
echo $dump->visit($ast);
