<?php

require __DIR__ . '/../../vendor/autoload.php';

use PhpYacc\Generator;
use PhpYacc\Grammar\Context;

const VERBOSE_DEBUG = false;

$generator = new Generator();

$grammar = __DIR__ . '/grammar.y';
$skeleton = __DIR__ . '/Parser.template';

$errorFile = fopen('php://stderr', 'w');
$debugFile = fopen('php://stdout', 'w');

$context = new Context($grammar, $errorFile, $debugFile, VERBOSE_DEBUG);

$generator->generate(
    $context,
    file_get_contents($grammar),
    file_get_contents($skeleton),
    __DIR__ . '/Parser.php'
);
