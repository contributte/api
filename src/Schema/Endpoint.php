<?php

namespace Contributte\Api\Schema;

final class Endpoint
{

	/** @var string */
	private $method;

	/** @var string */
	private $mask;

	/** @var string */
	private $pattern;

	/** @var EndpointHandler */
	private $handler;

	/** @var EndpointParam[] */
	private $params;

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 * @return void
	 */
	public function setMethod($method)
	{
		$this->method = $method;
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
		$this->params = $params;
	}

}
