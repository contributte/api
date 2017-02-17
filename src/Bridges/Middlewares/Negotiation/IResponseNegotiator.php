<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiDataResponse;

interface IResponseNegotiator
{

	/**
	 * @param ApiRequest $request
	 * @param ApiDataResponse $response
	 * @return ApiDataResponse|NULL
	 */
	public function negotiateResponse(ApiRequest $request, ApiDataResponse $response);

}
