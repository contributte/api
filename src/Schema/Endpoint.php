<?php

namespace Contributte\Api\Schema;

use Contributte\Api\Exception\Logical\InvalidArgumentException;

final class Endpoint
{

	/** @var array */
	private static $allowed = [
		'GET',
    'POST',
    'PUT',
    'DELETE',
    'OPTION',
	];

	/** @var string[] */
	private $methods = [];

	/** @var string */
	private $mask;

	/** @var string */
	private $pattern;

	/** @var EndpointHandler */
	private $handler;

	/** @var EndpointParam[] */
	private $params;

	/**
	 * @return string[]
	 */
	public function getMethods()
	{
		return $this->methods;
	}

	/**
	 * @param string[] $methods
	 * @return void
	 */
	public function setMethods(array $methods)
	{
		foreach ($methods as $method) {
			$this->addMethod($method);
		}
	}

	/**
	 * @param string $method
	 * @return void
	 */
	public function addMethod($method)
	{
		$method = strtoupper($method);

		if (!in_array($method, self::$allowed)) {
			throw new InvalidArgumentException(sprintf('Method %s is not allowed', $method));
		}

		$this->methods[] = $method;
	}

	/**
	 * @param string $method
	 * @return bool
	 */
	public function hasMethod($method)
	{
		return in_array(strtoupper($method), $this->methods);
	}

	/**
	 * @return string
	 */
	public function getMask()
	{
		return $this->mask;
	}

	/**
	 * @param string $mask
	 * @return void
	 */
	public function setMask($mask)
	{
		$this->mask = $mask;
	}

	/**
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @param string $pattern
	 * @return void
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;
	}

	/**
	 * @return EndpointHandler
	 */
	public function getHandler()
	{
		return $this->handler;
	}

	/**
	 * @param EndpointHandler $handler
	 * @return void
	 */
	public function setHandler($handler)
	{
		$this->handler = $handler;
	}

	/**
	 * @return EndpointParam[]
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasParam($name)
	{
		return isset($this->params[$name]);
	}

	/**
	 * @param EndpointParam $param
	 * @return void
	 */
	public function addParam(EndpointParam $param)
	{
		$this->params[$param->getName()] = $param;
	}

	/**
	 * @param EndpointParam[] $params
	 * @return void
	 */
	public function setParams(array $params)
	{
		foreach ($params as $param) {
			$this->addParam($param);
		}
	}

}
