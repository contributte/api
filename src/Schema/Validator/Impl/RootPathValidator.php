<?php

namespace Contributte\Api\Schema\Validator\Impl;

use Contributte\Api\Exception\Logical\Validation\InvalidSchemaException;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Validator\IValidator;

class RootPathValidator implements IValidator
{

	/** @var array */
	private $rootPaths = [];

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	public function validate(SchemaBuilder $builder)
	{
		foreach ($builder->getControllers() as $controller) {

			// If this RootPath exists, throw an exception
			if (array_key_exists($controller->getRootPath(), $this->rootPaths)) {
				throw new InvalidSchemaException(
					sprintf('Duplicate @RootPath in %s and %s', $controller->getClass(), $this->rootPaths[$controller->getRootPath()])
				);
			}

			$this->rootPaths[$controller->getRootPath()] = $controller->getClass();
		}
	}

}
