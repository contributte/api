<?php

namespace Contributte\Api\Http\Request;

use Contributte\Api\Schema\Endpoint;
use Psr\Http\Message\ServerRequestInterface;

class ApiRequest
{

	/** @var ServerRequestInterface */
	private $request;

	/** @var Endpoint */
	private $endpoint;

	/**
	 * @param ServerRequestInterface $request
	 * @return ApiRequest
	 */
	public function withRequest(ServerRequestInterface $request)
	{
		$new = clone $this;
		$new->request = $request;

		return $new;
	}

	/**
	 * @return ServerRequestInterface
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @param Endpoint $endpoint
	 * @return ApiRequest
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
