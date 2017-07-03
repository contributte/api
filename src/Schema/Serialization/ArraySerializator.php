<?php

namespace Contributte\Api\Schema\Generator;

use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\EndpointHandler;
use Contributte\Api\Schema\EndpointParameter;
use Contributte\Api\Schema\SchemaMapping;
use Contributte\Api\Utils\Helpers;
use Contributte\Api\Utils\Regex;

final class ArraySerializator implements ISerializator
{

	/**
	 * @param SchemaBuilder $builder
	 * @return array
	 */
	public function serialize(SchemaBuilder $builder)
	{
		$controllers = $builder->getControllers();
		$schema = [];

		// Iterate over all controllers
		foreach ($controllers as $controller) {

			// Iterate over all controller api methods
			foreach ($controller->getMethods() as $method) {

				// Skip invalid methods
				if (empty($method->getPath())) continue;

				// Build full mask (@RootPath + @Path)
				// without duplicated slashes (//)
				// and without trailing slash at the end
				$mask = '/' . $controller->getRootPath() . '/' . $method->getPath();
				$mask = Helpers::slashless($mask);
				$mask = rtrim($mask, '/');

				// Create endpoint
				$endpoint = [
					SchemaMapping::HANDLER => [
						SchemaMapping::HANDLER_TYPE => EndpointHandler::TYPE_CONTROLLER,
						SchemaMapping::HANDLER_CLASS => $controller->getClass(),
						SchemaMapping::HANDLER_METHOD => $method->getName(),
					],
					SchemaMapping::METHODS => $method->getMethods(),
					SchemaMapping::ROOT_PATH => $controller->getRootPath(),
					SchemaMapping::PATH => $method->getPath(),
					SchemaMapping::MASK => $mask,
					SchemaMapping::PARAMETERS => [],
					SchemaMapping::PATTERN => $mask,
				];

				// Collect variable parameters
				$pattern = Regex::replaceCallback($mask, '#({([a-zA-Z0-9\-_]+)})#U', function ($matches) use (&$endpoint) {
					list($whole, $variable, $variableName) = $matches;

					// Build parameter pattern
					$pattern = sprintf('(?P<%s>[^/]+)', $variableName);

					// Append to params
					$endpoint[SchemaMapping::PARAMETERS][$variableName] = [
						SchemaMapping::PARAMETERS_NAME => $variableName,
						// @todo type by annotation
						SchemaMapping::PARAMETERS_TYPE => EndpointParameter::TYPE_SCALAR,
						SchemaMapping::PARAMETERS_PATTERN => $pattern,
					];

					// Returned pattern replace {variable} in mask
					return $pattern;
				});

				// Build final regex pattern
				$endpoint[SchemaMapping::PATTERN] = sprintf('#%s$/?\z#A', $pattern);

				// Append to schema
				$schema[] = $endpoint;
			}
		}

		return $schema;
	}

}
