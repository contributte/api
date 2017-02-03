<?php

namespace Contributte\Api\Schema\Factory;

use Contributte\Api\Schema\ApiSchema;
use Contributte\Api\Schema\Endpoint;
use Contributte\Api\Schema\EndpointHandler;
use Contributte\Api\Schema\EndpointParam;
use Contributte\Api\Schema\SchemaMapping;

final class ArraySchemaFactory implements ISchemaFactory
{

	/** @var array */
	private $schema;

	/**
	 * @param array $schema
	 */
	public function __construct(array $schema)
	{
		$this->schema = $schema;
	}

	/**
	 * @return ApiSchema
	 */
	public function create()
	{
		$schema = new ApiSchema();

		foreach ($this->schema as $route) {
			// @todo replace to EndpointHandler::factory()
			// move validation inside, make class immutable
			$handler = new EndpointHandler();
			$handler->setClass($route[SchemaMapping::HANDLER][SchemaMapping::HANDLER_CLASS]);
			$handler->setMethod($route[SchemaMapping::HANDLER][SchemaMapping::HANDLER_METHOD]);
			//$handler->setMethod($route[SchemaMapping::HANDLER][SchemaMapping::HANDLER_TYPE]);

			$endpoint = new Endpoint();
			$endpoint->setHandler($handler);
			$endpoint->addMethod($route[SchemaMapping::METHOD]);
			$endpoint->setMask($route[SchemaMapping::MASK]);
			$endpoint->setPattern($route[SchemaMapping::PATTERN]);

			foreach ($route[SchemaMapping::PARAMS] as $p) {
				$param = new EndpointParam();
				$param->setName($p[SchemaMapping::PARAMS_NAME]);
				$param->setType(EndpointParam::TYPE_SCALAR);
				$endpoint->addParam($param);
			}

			$schema->addEndpoint($endpoint);
		}

		return $schema;
	}

}
