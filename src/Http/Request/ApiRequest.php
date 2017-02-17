<?php

namespace Contributte\Api\Http\Request;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\Param\AbstractParameter;
use Contributte\Api\Schema\Endpoint;
use Psr\Http\Message\ServerRequestInterface;

class ApiRequest
{

	// Attributes
	const ATTR_ROUTER_VARS = 'Router-Vars';

	/** @var ServerRequestInterface */
	protected $request;

	/** @var Endpoint */
	protected $endpoint;

	/** @var array */
	protected $parameters = [];

	/** @var array */
	protected $attributes = [];

	/**
	 * PSR-7 *******************************************************************
	 */

	/**
	 * @param ServerRequestInterface $request
	 * @return static
	 */
	public function withPsr7(ServerRequestInterface $request)
	{
		$new = clone $this;
		$new->request = $request;

		return $new;
	}

	/**
	 * @return ServerRequestInterface
	 */
	public function getPsr7()
	{
		return $this->request;
	}

	/**
	 * ENDPOINT ****************************************************************
	 */

	/**
	 * @param Endpoint $endpoint
	 * @return static
	 */
	public function withEndpoint(Endpoint $endpoint)
	{
		$new = clone $this;
		$new->endpoint = $endpoint;

		return $new;
	}

	/**
	 * @return Endpoint
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * PARAMETERS **************************************************************
	 */

	/**
	 * @param array $parameters
	 * @return static
	 */
	public function withParameters(array $parameters)
	{
		$new = clone $this;
		$new->parameters = $parameters;

		return $new;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return static
	 */
	public function withParameter($name, $value)
	{
		$new = clone $this;
		$new->parameters[$name] = $value;

		return $new;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasParameter($name)
	{
		return isset($this->parameters[$name]);
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return AbstractParameter
	 */
	public function getParameter($name, $default = NULL)
	{
		if (!$this->hasParameter($name)) {
			if (func_num_args() < 2) {
				throw new InvalidStateException('No parameter found');
			}

			return $default;
		}

		return $this->parameters[$name];
	}

	/**
	 * @return AbstractParameter[]
	 */
	public function getParameters()
	{
		return $this->parameters;
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
		$new = clone $this;
		$new->attributes = $attributes;

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
		return isset($this->attributes[$name]);
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return AbstractParameter
	 */
	public function getAttribute($name, $default = NULL)
	{
		if (!$this->hasAttribute($name)) {
			if (func_num_args() < 2) {
				throw new InvalidStateException('No attribute found');
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
