<?php

namespace Contributte\Api\Schema\Validator\Impl;

use Contributte\Api\Exception\Logical\Validation\InvalidSchemaException;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Validator\IValidator;

class PathValidator implements IValidator
{

	/** @var array */
	private $paths = [];

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	public function validate(SchemaBuilder $builder)
	{
		foreach ($builder->getControllers() as $controller) {

			foreach ($controller->getMethods() as $method) {
				// Init controller paths
				if (!isset($this->paths[$controller->getClass()])) {
					$this->paths[$controller->getClass()] = [];
				}

				// If this RootPath exists, throw an exception
				if (array_key_exists($method->getPath(), $this->paths[$controller->getClass()])) {
					throw new InvalidSchemaException(
						sprintf(
							'Duplicate @Path "%s" in %s at methods "%s()" and "%s()"',
							$method->getPath(),
							$controller->getClass(),
							$method->getName(),
							$this->paths[$controller->getClass()][$method->getPath()]
						)
					);
				}

				$this->paths[$controller->getClass()][$method->getPath()] = $method->getName();
			}
		}
	}

}
