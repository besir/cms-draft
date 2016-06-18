<?php

namespace Wunderman\CmsCore\DI;

use Doctrine\ORM\Tools\Setup;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\Helpers;
use Wunderman\CmsCore\DI\Factories\DoctrineFactory;
use Wunderman\CmsCore\DI\Factories\IDoctrineManagerFactory;

class Extension extends CompilerExtension
{
	use ExtensionHelper;

	protected $defaults = [
		'doctrine' => [
			'driver'   => 'pdo_mysql',
			'user'     => 'root',
			'password' => NULL,
			'dbname'   => '',
			'charset'  => 'utf8',
		]
	];

	protected function configure(ContainerBuilder $containerBuilder)
	{
		$parameters = $this->getConfig($this->defaults);

		$configurationDefinition = $containerBuilder->addDefinition($this->prefix('doctrine.configuration'))
			->setClass(Setup::class)
			->addSetup(
				'createConfiguration',
				[
					Helpers::expand('%debugMode%', $containerBuilder->parameters),
					Helpers::expand('%tempDir%/doctrine', $containerBuilder->parameters)
				]
			);

		$managerFactoryDefinition = $containerBuilder->addDefinition($this->prefix('doctrine.entity.manager.factory'))
			->setClass(DoctrineFactory::class, [$configurationDefinition, $parameters['doctrine']]);

		$containerBuilder->addDefinition($this->prefix('doctrine.entity.manager'))
			->setClass(IDoctrineManagerFactory::class)
			->setFactory($managerFactoryDefinition);
	}

}
