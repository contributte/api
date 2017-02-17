<?php

namespace Contributte\Api\Schema\Factory;

use Contributte\Api\Schema\ApiSchema;

interface IFactory
{

	/**
	 * @return ApiSchema
	 */
	public function create();

}
