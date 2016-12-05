<?php

namespace Contributte\Api\Rest\Binding;

abstract class AbstractRequestParam implements IRequestParam
{

	/** @var string */
	private $name;

	/** @var mixed */
	private $value;

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

}
