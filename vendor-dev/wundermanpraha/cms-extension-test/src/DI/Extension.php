<?php

namespace Wunderman\CmsExtensionTest\DI;

use Nette\DI\CompilerExtension;
use Wunderman\CmsCore\Helpers\ExtensionHelperTrait;

class Extension extends CompilerExtension
{
	use ExtensionHelperTrait;

	public function loadConfiguration()
	{
		$containerBuilder = $this->loadDefaultConfiguration(__DIR__);
		$containerBuilder->getDefinition('wunderman.cms.doctrine.entity.manager.factory')
			->addSetup('addPaths', [__DIR__ . '/../Modules']);
	}
}
