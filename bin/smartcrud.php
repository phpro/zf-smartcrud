<?php

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Application;

ini_set('display_errors', true);
chdir(__DIR__);
if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

$previousDir = '.';
while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());

    if ($previousDir === $dir) {
        throw new RuntimeException(
            'Unable to locate "config/application.config.php": ' .
            'is PhproSmartCrud in a subdir of your application skeleton?'
        );
    }

    $previousDir = $dir;
    chdir($dir);
}

$application = Application::init(include 'config/application.config.php');

/* @var $cli \Symfony\Component\Console\Application */
$cli = $application->getServiceManager()->get('zf-smartcrud.cli');
$cli->run();
