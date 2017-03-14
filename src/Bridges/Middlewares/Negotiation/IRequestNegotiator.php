<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

interface IRequestNegotiator
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiRequest|NULL
	 */
	public function negotiateRequest(ApiRequest $request, ApiResponse $response);

}
