<?php

namespace Contributte\Api\Http;

use Contributte\Psr7\Psr7Response;

class ApiResponse extends Psr7Response
{

	/** @var mixed */
	protected $data;

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
	public function withData($data)
	{
		$new = clone $this;
		$new->data = $data;

		return $new;
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
	public function writeJsonBody(array $data)
	{
		return $this->withHeader('Content-Type', 'application/json')
			->writeBody(json_encode($data), TRUE);
	}

}
