<?php

namespace Contributte\Api\Http\Request;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\Param\AbstractParameter;
use Contributte\Api\Schema\Endpoint;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ApiRequest
{

	// Attributes
	const ATTR_ROUTER_VARS = 'Router-Vars';

	/** @var ServerRequestInterface */
	protected $request;

	/** @var array */
	protected $data;

	/** @var Endpoint */
	protected $endpoint;

	/** @var array */
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
		if (!$this->request) {
			throw new InvalidStateException('ServerRequestInterface is missing, please call withPsr7($request)');
		}

		return $this->request;
	}

	/**
	 * PSR-7 API ***************************************************************
	 */

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->getPsr7()->getMethod();
	}

	/**
	 * @return StreamInterface
	 */
	public function getBody()
	{
		return $this->getPsr7()->getBody();
	}

	/**
	 * @param mixed $body
	 * @return static
	 */
	public function setBody($body)
	{
		$this->getPsr7()
			->getBody()
			->write($body);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getContents()
	{
		return $this->getBody()->getContents();
	}

	/**
	 * @return mixed
	 */
	public function getParsedBody()
	{
		return $this->getPsr7()->getParsedBody();
	}

	/**
	 * @return UriInterface
	 */
	public function getUri()
	{
		return $this->getPsr7()->getUri();
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
				throw new InvalidStateException(sprintf('No parameter "%s" found', $name));
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
		foreach ($attributes as $k => $v) {
			$this->withAttribute($k, $v);
		}

		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return static
	 */
	public function withAttribute($name, $value)
	{
		$this->request = $this->getPsr7()
			->withAttribute($name, $value);

		return $this;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasAttribute($name)
	{
		$attrs = $this->getPsr7()->getAttributes();

		return array_key_exists($name, $attrs);
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
				throw new InvalidStateException(sprintf('No attribute "%s" found', $name));
			}

			return $default;
		}

		return $this->getPsr7()->getAttribute($name, $default);
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->getPsr7()->getAttributes();
	}

	/**
	 * QUERY PARAM *************************************************************
	 */

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasQueryParam($name)
	{
		return array_key_exists($name, $this->getPsr7()->getQueryParams());
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return AbstractParameter
	 */
	public function getQueryParam($name, $default = NULL)
	{
		if (!$this->hasQueryParam($name)) {
			if (func_num_args() < 2) {
				throw new InvalidStateException(sprintf('No query parameter "%s" found', $name));
			}

			return $default;
		}

		return $this->getPsr7()->getQueryParams()[$name];
	}

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
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

}
