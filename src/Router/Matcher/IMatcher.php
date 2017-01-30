<?php

namespace Contributte\Api\Router\Matcher;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Schema\Endpoint;

interface IMatcher
{

	/**
	 * @param Endpoint $endpoint
	 * @param ApiRequest $request
	 * @return ApiRequest
	 */
	public function match(Endpoint $endpoint, ApiRequest $request);

}
