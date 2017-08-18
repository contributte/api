<?php

namespace Contributte\Api\Http;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Psr7\Psr7Response;

class ApiResponse extends Psr7Response
{

	/** @var mixed */
	protected $data;

	/** @var array */
	private $attributes = [];

	/**
	 * DATA ********************************************************************
	 */

	/**
	 * @return bool
	 */
	public function hasData()
	{
		return $this->data !== NULL;
	}

	/**
	 * @param mixed $data
	 * @return static
	 */
	public function withData($data)
	{
		$new = clone $this;
		$new->data = $data;

		return $new;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * ATTRIBUTES **************************************************************
	 */

	/**
	 * @param array $attributes
	 * @return static
	 */
	public function withAttributes(array $attributes)
	{
		$new = $this;
		foreach ($attributes as $k => $v) {
			$new = $this->withAttribute($k, $v);
		}

		return $new;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return static
	 */
	public function withAttribute($name, $value)
	{
		$new = clone $this;
		$new->attributes[$name] = $value;

		return $new;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasAttribute($name)
	{
		return array_key_exists($name, $this->attributes);
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getAttribute($name, $default = NULL)
	{
		if (!$this->hasAttribute($name)) {
			if (func_num_args() < 2) {
				throw new InvalidStateException(sprintf('Attribute "%s" not found', $name));
			}

			return $default;
		}

		return $this->attributes[$name];
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

}
