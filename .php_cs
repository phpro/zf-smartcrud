<?php
$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('vendor')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/spec')
    ->in(__DIR__ . '/bin')
    ->in(__DIR__ . '/view')
    ->in(__DIR__ . '/config');
$config = Symfony\CS\Config\Config::create();
$config->fixers(Symfony\CS\FixerInterface::PSR2_LEVEL);
$config->finder($finder);
return $config;
