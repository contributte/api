<?php

namespace Contributte\Api\UI;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;

interface IHandler
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	public function handle(ApiRequest $request, ApiResponse $response);

}
