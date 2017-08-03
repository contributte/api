<?php

namespace Contributte\Api\Schema\Serialization;

use Contributte\Api\Schema\ApiSchema;

interface IHydrator
{

	/**
	 * @param mixed $data
	 * @return ApiSchema
	 */
	public function hydrate($data);

}
