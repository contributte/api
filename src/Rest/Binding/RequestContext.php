<?php

namespace Contributte\Api\Rest\Binding;

use Contributte\Api\Schema\Endpoint;

final class RequestContext
{

	/** @var Endpoint */
	private $endpoint;

	/** @var IRequestParam[] */
	private $params;

	/**
	 * @param Endpoint $endpoint
	 * @param IRequestParam[] $params
	 */
	public function __construct(Endpoint $endpoint, array $params)
	{
		$this->endpoint = $endpoint;
		$this->params = $params;
	}

	/**
	 * @return Endpoint
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * @return AbstractRequestParam[]
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @return bool
	 */
	public function hasParams()
	{
		return count($this->params) > 0;
	}

}
