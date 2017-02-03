<?php

namespace Contributte\Api\Http\Request\Param;

use Contributte\Api\Exception\Logical\InvalidTypeException;

class ScalarParameter extends AbstractParameter
{

	/**
	 * @param mixed $value
	 * @return void
	 */
	public function parse($value)
	{
		if (!is_scalar($value)) {
			throw new InvalidTypeException('Scalar value expected');
		}

		$this->value = $value;
	}

}
