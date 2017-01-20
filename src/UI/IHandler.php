<?php

namespace Contributte\Api\UI;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

interface IHandler
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	public function handle(ApiRequest $request, ApiResponse $response);

}
