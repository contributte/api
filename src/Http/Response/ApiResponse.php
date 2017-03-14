<?php

namespace Contributte\Api\Http\Response;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Psr\Http\Message\ResponseInterface;

class ApiResponse
{

	/** @var ResponseInterface */
	protected $response;

	/** @var mixed */
	protected $data;

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
		if (!$this->response) {
			throw new InvalidStateException('ResponseInterface is missing, please call withPsr7($response)');
		}

		return $this->response;
	}

	/**
	 * PSR-7 API ***************************************************************
	 */

	/**
	 * @param int $code
	 * @return static
	 */
	public function setStatus($code)
	{
		$this->response = $this->response
			->withStatus($code);

		return $this;
	}

	/**
	 * @param string $header
	 * @param string $value
	 * @return static
	 */
	public function setHeader($header, $value)
	{
		$this->response = $this->getPsr7()
			->withHeader($header, $value);

		return $this;
	}

	/**
	 * @param string $header
	 * @return mixed
	 */
	public function getHeader($header)
	{
		return $this->getPsr7()
			->getHeader($header);
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
	public function getBody()
	{
		return $this->getPsr7()
			->getBody()
			->getContents();
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

	/**
	 * @param array $data
	 * @return static
	 */
	public function setJson(array $data)
	{
		$this->setHeader('Content-Type', 'application/json');
		$this->setBody(json_encode($data));

		return $this;
	}

}
