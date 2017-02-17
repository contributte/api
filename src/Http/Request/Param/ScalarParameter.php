<?php

namespace Contributte\Api\Http\Request\Param;

use Contributte\Api\Exception\Logical\InvalidValueTypeException;

class ScalarParameter extends AbstractParameter
{

	/**
	 * @param mixed $value
	 * @return void
	 */
	public function setValue($value)
	{
		if (!is_scalar($value)) {
			throw new InvalidValueTypeException('Scalar value expected');
		}

		$this->value = $value;
	}

}
