<?php

namespace Wunderman\CmsCore\DI\Factories;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

class DoctrineFactory implements IDoctrineManagerFactory
{
	/** @var Configuration */
	protected $configuration;

	/** @var AnnotationDriver */
	protected $annotationDriver;

	/** @var array */
	protected $parameters;

	public function __construct(Configuration $configuration, array $parameters)
	{
		$this->configuration = $configuration;
		$this->parameters = $parameters;
		$this->annotationDriver = $this->configuration->newDefaultAnnotationDriver([], FALSE);
		$this->configuration->setMetadataDriverImpl($this->annotationDriver);
	}

	public function addPaths(array $paths)
	{
		$this->annotationDriver->addPaths($paths);
	}

	/**
	 * @return EntityManager
	 * @throws \Doctrine\ORM\ORMException
	 */
	public function create()
	{
		return EntityManager::create($this->parameters, $this->configuration);
	}
}
