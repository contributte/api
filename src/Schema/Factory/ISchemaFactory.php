<?php

namespace Contributte\Api\Schema\Factory;

use Contributte\Api\Schema\ApiSchema;

interface ISchemaFactory
{

	/**
	 * @return ApiSchema
	 */
	public function create();

}
