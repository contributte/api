<?php

namespace Contributte\Api\Schema\Validation;

use Contributte\Api\Schema\Builder\SchemaBuilder;

interface IValidation
{

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	public function validate(SchemaBuilder $builder);

}
