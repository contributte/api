<?php

namespace Contributte\Api\Http\Request;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\Param\AbstractParameter;
use Contributte\Api\Schema\Endpoint;
use Psr\Http\Message\ServerRequestInterface;

class ApiRequest
{

	/** @var ServerRequestInterface */
	protected $request;

	/** @var Endpoint */
	protected $endpoint;

	/** @var AbstractParameter[] */
	protected $parameters = [];

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
	 * @param string $name
	 * @param AbstractParameter $parameter
	 * @return void
	 */
	public function addParameter($name, AbstractParameter $parameter)
	{
		$this->parameters[$name] = $parameter;
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

}
