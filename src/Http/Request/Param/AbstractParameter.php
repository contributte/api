<?php

namespace Contributte\Api\Http\Request\Param;

abstract class AbstractParameter
{

	/** @var mixed */
	protected $value;

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * PARSING *****************************************************************
	 */

	/**
	 * @param mixed $value
	 * @return void
	 */
	abstract public function parse($value);

	/**
	 * MAGIC *******************************************************************
	 */

	/**
	 * @return mixed
	 */
	public function __toString()
	{
		return $this->value;
	}

}
