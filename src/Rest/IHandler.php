<?php

namespace Contributte\Api\Rest;

use Contributte\Api\Http\Response\IApiResponse;
use Contributte\Api\Rest\Binding\RequestContext;

interface IHandler
{

	/**
	 * @param RequestContext $context
	 * @return IApiResponse
	 */
	public function process(RequestContext $context);

}
