<?php

namespace Contributte\Api\Schema\Generator;

use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\EndpointHandler;
use Contributte\Api\Schema\EndpointParameter;
use Contributte\Api\Schema\SchemaMapping;
use Contributte\Api\Utils\Helpers;
use Nette\Utils\Strings;

final class ArrayGenerator implements IGenerator
{

	/**
	 * @param SchemaBuilder $builder
	 * @return array
	 */
	public function generate(SchemaBuilder $builder)
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
					SchemaMapping::METHOD => $method->getMethod(),
					SchemaMapping::ROOT_PATH => $controller->getRootPath(),
					SchemaMapping::PATH => $method->getPath(),
					SchemaMapping::MASK => $mask,
					SchemaMapping::PARAMETERS => [],
					SchemaMapping::PATTERN => $mask,
				];

				// Collect variable parameters
				$params = Strings::matchAll($mask, '#{(.+)}#U');
				foreach ($params as $param) {
					list ($wholeParam, $paramName) = $param;

					// Create endpoint param
					$endpointParam = [
						SchemaMapping::PARAMS_NAME => $paramName,
						// @todo type by annotation
						SchemaMapping::PARAMS_TYPE => EndpointParameter::TYPE_SCALAR,
					];

					// Append to params
					$endpoint[SchemaMapping::PARAMETERS][$paramName] = $endpointParam;

					// Replace param in mask
					// @todo pattern by param type
					$endpoint[SchemaMapping::PATTERN] = str_replace($wholeParam, sprintf('(?P<%s>[^/]+)', $paramName), $endpoint[SchemaMapping::PATTERN]);
				}

				// Build final regex pattern
				$endpoint[SchemaMapping::PATTERN] = sprintf('#^%s$#', $endpoint[SchemaMapping::PATTERN]);

				// Append to scheme
				$schema[$mask] = $endpoint;
			}
		}

		return $schema;
	}

}
