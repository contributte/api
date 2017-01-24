<?php

namespace Contributte\Api\Http\Response;

class ApiDataResponse extends ApiResponse
{

	/** @var mixed */
	protected $data;

	/**
	 * API *********************************************************************
	 */

	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return $this->data !== NULL;
	}

	/**
	 * @param mixed $data
	 * @return static
	 */
	public function write($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function fetch()
	{
		return $this->data;
	}

}
