<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

interface IInvoker
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @param callable $next
	 * @return ApiResponse
	 */
	public function invoke(ApiRequest $request, ApiResponse $response, callable $next);

}
