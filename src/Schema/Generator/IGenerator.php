<?php

namespace Contributte\Api\Schema\Generator;

use Contributte\Api\Schema\Builder\SchemaController;

interface IGenerator
{

	/**
	 * @param SchemaController[] $controllers
	 * @return mixed
	 */
	public function generate(array $controllers);

}
