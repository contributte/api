<?php

namespace Contributte\Api\Schema\Generator;

use Contributte\Api\Schema\Builder\SchemaController;
use Contributte\Api\Schema\EndpointHandler;
use Contributte\Api\Schema\EndpointParam;
use Contributte\Api\Schema\SchemaMapping;
use Contributte\Api\Utils\Helpers;
use Nette\Utils\Strings;

final class ArrayGenerator implements IGenerator
{

	/**
	 * @param SchemaController[] $controllers
	 * @return array
	 */
	public function generate(array $controllers)
	{
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
					SchemaMapping::PARAMS => [],
					SchemaMapping::PATTERN => $mask,
				];

				// Collect variable parameters
				$params = Strings::matchAll($mask, '#{(.+)}#U');
				foreach ($params as $param) {
					list ($wholeParam, $paramName) = $param;

					// Create endpoint param
					$endpointParam = [
						SchemaMapping::PARAMS_NAME => $paramName,
						SchemaMapping::PARAMS_TYPE => EndpointParam::TYPE_SCALAR,
					];

					// Append to params
					$endpoint[SchemaMapping::PARAMS][$paramName] = $endpointParam;

					// Replace param in mask
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
