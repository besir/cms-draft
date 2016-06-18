<?php

namespace Wunderman\CmsCore\DI\Factories;

use Doctrine\ORM\EntityManagerInterface;

interface IDoctrineManagerFactory
{
	/**
	 * @return EntityManagerInterface
	 */
	public function create();
}
