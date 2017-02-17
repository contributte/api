<?php

namespace Contributte\Api\Http\Request\Param;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Schema\EndpointParameter;

class ParametersMapper
{

	/** @var array */
	public static $mapping = [
		EndpointParameter::TYPE_SCALAR => ScalarParameter::class,
		EndpointParameter::TYPE_STRING => StringParameter::class,
	];

	/**
	 * @param int $type
	 * @param mixed $value
	 * @return AbstractParameter
	 */
	public static function parse($type, $value)
	{
		if (!isset(self::$mapping[$type])) {
			throw new InvalidStateException('Unsupported mapping type');
		}

		/** @var AbstractParameter $param */
		$param = new self::$mapping[$type]();
		$param->setValue($value);

		return $param;
	}

}
