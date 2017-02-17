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
	 * @param mixed $value
	 * @return void
	 */
	abstract public function setValue($value);

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
