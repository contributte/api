<?php

namespace Contributte\Api\Http\Request;

use Contributte\Api\Schema\Endpoint;
use Psr\Http\Message\ServerRequestInterface;

class ApiRequest
{

	/** @var ServerRequestInterface */
	protected $request;

	/** @var Endpoint */
	protected $endpoint;

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

}
