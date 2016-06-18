<?php

namespace Wunderman\CmsCore\Helpers;

use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\InvalidArgumentException;

trait ExtensionHelperTrait
{
	public function loadDefaultConfiguration($dir)
	{
		/** @var ContainerBuilder $builder */
		/** @var CompilerExtension $this */
		$builder = $this->getContainerBuilder();
		$config = $this->loadFromFile($dir . '/../config.neon');

		if (empty($config['name'])) {
			throw new InvalidArgumentException('Parameter "name" must be set');
		}

		$extensionDir = realpath($dir . '/../..');
		$builder->parameters['wunderman-cms']['extensions'][$extensionDir] = [
			'name' => $config['name'],
			'dir'  => $extensionDir
		];

		if (!empty($config['mapping'])) {
			$presenterFactory = $builder->getDefinition('application.presenterFactory');
			$presenterFactory->addSetup('setMapping', [[$config['name'] => $config['mapping']]]);
		}

		if (!empty($config['routes'])) {
			$router = $builder->getDefinition('routing.router');

			foreach ($config['routes'] as $mask => $action) {
				$router->addSetup('$service[] = new Nette\Application\Routers\Route(?, ?);', [$mask, $action]);
			}
		}

		return $builder;
	}
}
