<?php

namespace Contributte\Api\Http\Request\Param;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Schema\EndpointParam;

class ParametersMapper
{

	/** @var array */
	public static $mapping = [
		EndpointParam::TYPE_SCALAR => ScalarParameter::class,
		EndpointParam::TYPE_STRING => StringParameter::class,
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
		$param->parse($value);

		return $param;
	}

}
