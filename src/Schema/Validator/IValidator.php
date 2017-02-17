<?php

namespace Contributte\Api\Schema\Validator;

use Contributte\Api\Schema\Builder\SchemaBuilder;

interface IValidator
{

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	public function validate(SchemaBuilder $builder);

}
