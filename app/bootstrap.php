<?php
use Composer\Autoload\ClassLoader;
use Nette\Configurator;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator();
$configurator->setDebugMode(TRUE);
$configurator->enableDebugger(__DIR__ . '/../var/log');
$configurator->setTempDirectory(__DIR__ . '/../var/temp');
$configurator->addConfig(__DIR__ . '/config/config.neon');

if (is_file(__DIR__ . '/config/config.local.neon')) {
	$configurator->addConfig(__DIR__ . '/config/config.local.neon');
}

$container = $configurator->createContainer();

return $container;
