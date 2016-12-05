<?php

namespace Contributte\Api\Schema\Builder;

use Contributte\Api\Schema\Generator\IGenerator;

final class SchemaBuilder
{

	/** @var SchemaController[] */
	private $controllers;

	/**
	 * @param string $class
	 * @return SchemaController
	 */
	public function addController($class)
	{
		$controller = new SchemaController($class);
		$this->controllers[$class] = $controller;

		return $controller;
	}

	/**
	 * @return SchemaController[]
	 */
	public function getControllers()
	{
		return $this->controllers;
	}

	/**
	 * @return mixed
	 */
	public function generate(IGenerator $generator)
	{
		return $generator->generate($this->controllers);
	}

}
