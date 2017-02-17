<?php

namespace Contributte\Api\Schema\Validator;

use Contributte\Api\Schema\Builder\SchemaBuilder;

class SchemaBuilderValidator implements IValidator
{

	/** @var IValidator[] */
	private $validators = [];

	/**
	 * @param IValidator $validator
	 * @return void
	 */
	public function add(IValidator $validator)
	{
		$this->validators[] = $validator;
	}

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	public function validate(SchemaBuilder $builder)
	{
		foreach ($this->validators as $validator) {
			$validator->validate($builder);
		}
	}

}
