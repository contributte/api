<?php

namespace Contributte\Api\Schema\Generator;

use Contributte\Api\Schema\Builder\SchemaBuilder;

interface ISerializator
{

	/**
	 * @param SchemaBuilder $builder
	 * @return mixed
	 */
	public function serialize(SchemaBuilder $builder);

}
