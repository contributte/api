<?php

namespace Contributte\Api\Http\Response;

use Psr\Http\Message\ResponseInterface;

class ApiResponse
{

	/** @var ResponseInterface */
	private $response;

	/**
	 * @param ResponseInterface $response
	 * @return ApiResponse
	 */
	public function withResponse(ResponseInterface $response)
	{
		$new = clone $this;
		$new->response = $response;

		return $new;
	}

	/**
	 * PSR-7 *******************************************************************
	 */

	/**
	 * @return ResponseInterface
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param mixed $data
	 * @return static
	 */
	public function write($data)
	{
		$this->response->getBody()->write($data);

		return $this;
	}

	/**
	 * @param array $data
	 * @return static
	 */
	public function json(array $data)
	{
		$this->write(json_encode($data));
		$this->response = $this->response->withHeader('Content-type', 'application/json');

		return $this;
	}

	/**
	 * @param int $code
	 * @return static
	 */
	public function status($code)
	{
		$this->response = $this->response->withStatus($code);

		return $this;
	}

}
