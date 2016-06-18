<?php
namespace Wunderman\CmsCore\Modules\AdminModule\Presenters;

use Doctrine\ORM\EntityManager;
use Nette;
use Nette\Application\UI\Presenter;

class ExtensionsPresenter extends Presenter
{
	/**
	 * @var EntityManager
	 */
	public $em;

	public function actionList()
	{
		$this->sendJson(['ff']);
	}
}
