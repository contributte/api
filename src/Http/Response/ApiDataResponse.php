<?php

namespace Contributte\Api\Http\Response;

class ApiDataResponse extends ApiResponse
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
		return $this->setData(json_encode($data));
	}

}
