<?php

namespace Contributte\Api\Schema\Generator;

use Contributte\Api\Schema\Builder\SchemaBuilder;

interface IGenerator
{

	/**
	 * @param SchemaBuilder $builder
	 * @return mixed
	 */
	public function generate(SchemaBuilder $builder);

}
