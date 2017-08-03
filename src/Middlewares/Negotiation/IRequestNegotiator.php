<?php

namespace Contributte\Api\Middlewares\Negotiation;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;

interface IRequestNegotiator
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiRequest|NULL
	 */
	public function negotiateRequest(ApiRequest $request, ApiResponse $response);

}
