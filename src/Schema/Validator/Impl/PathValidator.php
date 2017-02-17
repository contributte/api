<?php

namespace Contributte\Api\Schema\Validator\Impl;

use Contributte\Api\Exception\Logical\Validation\InvalidSchemaException;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Validator\IValidator;
use Contributte\Api\Utils\Regex;

class PathValidator implements IValidator
{

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	public function validate(SchemaBuilder $builder)
	{
		$this->validateDuplicities($builder);
		$this->validateSlashes($builder);
		$this->validateRegex($builder);
	}

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	public function validateDuplicities(SchemaBuilder $builder)
	{
		$controllers = $builder->getControllers();
		$paths = [];

		foreach ($controllers as $controller) {
			foreach ($controller->getMethods() as $method) {
				// Init controller paths
				if (!isset($paths[$controller->getClass()])) {
					$paths[$controller->getClass()] = [];
				}

				// If this RootPath exists, throw an exception
				if (array_key_exists($method->getPath(), $paths[$controller->getClass()])) {
					throw new InvalidSchemaException(
						sprintf(
							'Duplicate @Path "%s" in %s at methods "%s()" and "%s()"',
							$method->getPath(),
							$controller->getClass(),
							$method->getName(),
							$paths[$controller->getClass()][$method->getPath()]
						)
					);
				}

				$paths[$controller->getClass()][$method->getPath()] = $method->getName();
			}
		}
	}

	/**
	 * @param SchemaBuilder $builder
	 */
	protected function validateSlashes(SchemaBuilder $builder)
	{
		$controllers = $builder->getControllers();

		foreach ($controllers as $controller) {
			foreach ($controller->getMethods() as $method) {
				$path = $method->getPath();

				// MUST: Starts with slash (/)
				if (substr($path, 0, 1) !== '/') {
					throw new InvalidSchemaException(
						sprintf('@Path "%s" in "%s::%s()" must starts with "/" (slash).', $path, $controller->getClass(), $method->getName())
					);
				}

				// MUST NOT: Ends with slash (/), except single '/' path
				if (substr($path, -1, 1) === '/' && strlen($path) > 1) {
					throw new InvalidSchemaException(
						sprintf('@Path "%s" in "%s::%s()" must not ends with "/" (slash).', $path, $controller->getClass(), $method->getName())
					);
				}
			}
		}
	}

	/**
	 * @param SchemaBuilder $builder
	 */
	protected function validateRegex(SchemaBuilder $builder)
	{
		$controllers = $builder->getControllers();

		foreach ($controllers as $controller) {
			foreach ($controller->getMethods() as $method) {
				$path = $method->getPath();

				// Allowed characters:
				// -> a-z
				// -> A-Z
				// -> 0-9
				// -> -_/{}
				$match = Regex::match($path, '#([^a-zA-Z0-9\-_/{}]+)#');

				if ($match !== NULL) {
					throw new InvalidSchemaException(
						sprintf(
							'@Path "%s" in "%s::%s()" contains illegal characters "%s". Allowed characters are only [a-zA-Z0-9-_/{}].',
							$path,
							$controller->getClass(),
							$method->getName(),
							$match[1]
						)
					);
				}

				// Allowed parameter characters:
				// -> a-z
				// -> A-Z
				// -> 0-9
				// -> -_
				$match = Regex::match($path, '#\{.*([^a-zA-Z0-9\-_]+).*\}#');

				if ($match !== NULL) {
					throw new InvalidSchemaException(
						sprintf(
							'@Path "%s" in "%s::%s()" contains illegal characters "%s" in parameter. Allowed characters in parameter are only {[a-z-A-Z0-9-_]+}',
							$path,
							$controller->getClass(),
							$method->getName(),
							$match[1]
						)
					);
				}

			}
		}
	}

}
