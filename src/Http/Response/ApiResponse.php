<?php

namespace Contributte\Api\Http\Response;

use Psr\Http\Message\ResponseInterface;

class ApiResponse
{

	/** @var ResponseInterface */
	protected $response;

	/**
	 * PSR-7 *******************************************************************
	 */

	/**
	 * @param ResponseInterface $response
	 * @return static
	 */
	public function withPsr7(ResponseInterface $response)
	{
		$new = clone $this;
		$new->response = $response;

		return $new;
	}

	/**
	 * @return ResponseInterface
	 */
	public function getPsr7()
	{
		return $this->response;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param int $code
	 * @return static
	 */
	public function withStatus($code)
	{
		$this->response = $this->response->withStatus($code);

		return $this;
	}

}
