<?php

namespace Contributte\Api\Schema\Factory;

use Contributte\Api\Schema\ApiSchema;

interface IHydrator
{

	/**
	 * @param mixed $data
	 * @return ApiSchema
	 */
	public function hydrate($data);

}
