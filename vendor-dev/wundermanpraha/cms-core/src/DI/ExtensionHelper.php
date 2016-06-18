<?php

namespace Wunderman\CmsCore\DI;

use Nette\DI\ContainerBuilder;

trait ExtensionHelper
{
	public function loadConfiguration()
	{
		/** @var ContainerBuilder $builder */
		$builder = $this->getContainerBuilder();
		$config = $this->loadFromFile(__DIR__ . '/../config.neon');

		if (!empty($config['mapping'])) {
			$presenterFactory = $builder->getDefinition('application.presenterFactory');
			$presenterFactory->addSetup('setMapping', [$config['mapping']]);
		}

		if (!empty($config['routes'])) {
			$router = $builder->getDefinition('routing.router');

			foreach ($config['routes'] as $mask => $action) {
				$router->addSetup('$service[] = new Nette\Application\Routers\Route(?, ?);', [$mask, $action]);
			}
		}

		$this->configure($builder);
	}

	public function beforeCompile()
	{
	}

	protected function configure(ContainerBuilder $containerBuilder)
	{
	}
}
