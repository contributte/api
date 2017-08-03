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
		$new = $this->withHeader('Content-Type', 'application/json');
		$new->setBody(json_encode($data));

		return $new;
	}

}
