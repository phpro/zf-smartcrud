<?php

(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

// @TODO remove the requires - test in skeleton application
require_once(__DIR__ . '/../src/PhproSmartCrud/Console/Application.php');
require_once(__DIR__ . '/../src/PhproSmartCrud/Console/Command/Controller/Generate.php');

use \PhproSmartCrud\Console\Application;

$application = new Application();
$application->run();

