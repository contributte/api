<?php

namespace Contributte\Api\Http\Request\Param;

use Contributte\Api\Exception\Logical\InvalidTypeException;

class StringParameter extends AbstractParameter
{

	/**
	 * PARSING *****************************************************************
	 */

	/**
	 * @param mixed $value
	 * @return void
	 */
	public function parse($value)
	{
		if (!is_string($value)) {
			throw new InvalidTypeException('String value expected');
		}

		parent::parse(strval($value));
	}

}
