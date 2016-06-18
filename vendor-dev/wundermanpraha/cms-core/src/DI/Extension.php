<?php

namespace Wunderman\CmsCore\DI;

use Doctrine\ORM\EntityManagerInterface;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;
use Wunderman\CmsCore\DI\Factories\DoctrineFactory;
use Wunderman\CmsCore\Helpers\ExtensionHelperTrait;

class Extension extends CompilerExtension
{
	use ExtensionHelperTrait;

	protected $defaults = [
		'doctrine' => [
			'driver'   => 'pdo_mysql',
			'user'     => 'root',
			'password' => NULL,
			'dbname'   => '',
			'charset'  => 'utf8',
		]
	];

	public function loadConfiguration()
	{
		$parameters = $this->getConfig($this->defaults);
		$containerBuilder = $this->loadDefaultConfiguration(__DIR__);
		$containerBuilder->addDefinition('wunderman.cms.doctrine.entity.manager.factory')
			->setClass(
				DoctrineFactory::class,
				[
					Helpers::expand('%debugMode%', $containerBuilder->parameters),
					Helpers::expand('%tempDir%/doctrine', $containerBuilder->parameters),
					$parameters['doctrine']
				]
			)
			->addSetup('addPaths', [__DIR__ . '/../Modules']);

		$containerBuilder->addDefinition($this->prefix('doctrine.entity.manager'))
			->setClass(EntityManagerInterface::class)
			->setFactory('@wunderman.cms.doctrine.entity.manager.factory::create');
	}
}
