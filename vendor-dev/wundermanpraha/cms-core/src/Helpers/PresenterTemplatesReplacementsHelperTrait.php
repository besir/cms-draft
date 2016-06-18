<?php

namespace Wunderman\CmsCore\Helpers;

use Nette\Application\Helpers;
use Nette\Application\UI\Presenter;

trait PresenterTemplatesReplacementsHelperTrait
{
	/**
	 * Formats layout template file names.
	 * @return array
	 */
	public function formatLayoutTemplateFiles()
	{
		/** @var Presenter $this */
		if (preg_match('#/|\\\\#', $this->layout)) {
			return [$this->layout];
		}
		list($module, $presenter) = Helpers::splitName($this->getName());
		$layout = $this->layout ? $this->layout : 'layout';
		$dir = dirname($this->getReflection()->getFileName());
		$appDir = $this->context->parameters['appDir'];

		$list = [];

		if (is_dir($appDir . '/templates')) {
			$action = $this->getAction(TRUE);
			$actionParts = explode(':', $action);
			$tmp = $actionParts[2];
			$actionParts[2] = $actionParts[1];
			$actionParts[1] = $tmp;
			array_pop($actionParts);

			$first = TRUE;

			do {
				$replacementPath = implode('/', $actionParts);
				$list[] = $appDir . '/templates' . $replacementPath . "/@$layout.latte";

				if ($first) {
					$list[] = $appDir . '/templates' . $replacementPath . ".@$layout.latte";
					$first = FALSE;
				}

				array_pop($actionParts);
			} while ($actionParts);
		}

		$dir = is_dir("$dir/templates") ? $dir : dirname($dir);

		$list[] = "$dir/templates/$presenter/@$layout.latte";
		$list[] = "$dir/templates/$presenter.@$layout.latte";

		do {
			$list[] = "$dir/templates/@$layout.latte";
			$dir = dirname($dir);
		} while ($dir && $module && (list($module) = Helpers::splitName($module)));

		return $list;
	}

	/**
	 * Formats view template file names.
	 * @return array
	 */
	public function formatTemplateFiles()
	{
		/** @var Presenter $this */
		list(, $presenter) = Helpers::splitName($this->getName());
		$dir = dirname($this->getReflection()->getFileName());
		$appDir = $this->context->parameters['appDir'];
		$list = [];

		if (is_dir($appDir . '/templates')) {
			$action = $this->getAction(TRUE);
			$actionParts = explode(':', $action);
			$tmp = $actionParts[2];
			$actionParts[2] = $actionParts[1];
			$actionParts[1] = $tmp;
			$actionName = array_pop($actionParts);

			$replacementPath = implode('/', $actionParts);
			$list[] = "$appDir/templates$replacementPath/$actionName.latte";
			$list[] = "$appDir/templates$replacementPath.$actionName.latte";
		}

		$dir = is_dir("$dir/templates") ? $dir : dirname($dir);
		$list[] = "$dir/templates/$presenter/$this->view.latte";
		$list[] = "$dir/templates/$presenter.$this->view.latte";

		return $list;
	}
}
