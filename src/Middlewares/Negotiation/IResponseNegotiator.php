<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

interface IResponseNegotiator
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse|NULL
	 */
	public function negotiateResponse(ApiRequest $request, ApiResponse $response);

}
