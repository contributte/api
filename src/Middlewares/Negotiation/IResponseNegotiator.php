<?php

namespace Contributte\Api\Middlewares\Negotiation;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;

interface IResponseNegotiator
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse|NULL
	 */
	public function negotiateResponse(ApiRequest $request, ApiResponse $response);

}
