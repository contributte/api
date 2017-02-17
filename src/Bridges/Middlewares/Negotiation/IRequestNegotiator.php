<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiDataResponse;

interface IRequestNegotiator
{

	/**
	 * @param ApiRequest $request
	 * @param ApiDataResponse $response
	 * @return ApiRequest|NULL
	 */
	public function negotiateRequest(ApiRequest $request, ApiDataResponse $response);

}
