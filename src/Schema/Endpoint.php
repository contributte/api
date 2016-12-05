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
	 */
	public function addParam(EndpointParam $param)
	{
		$this->params[$param->getName()] = $param;
	}

	/**
	 * @param EndpointParam[] $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}

}
