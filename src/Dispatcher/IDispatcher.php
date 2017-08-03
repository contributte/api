<?php

namespace Contributte\Api\Dispatcher;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;

interface IDispatcher
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	public function dispatch(ApiRequest $request, ApiResponse $response);

}
