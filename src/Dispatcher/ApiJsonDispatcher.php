<?php

namespace Contributte\Api\Dispatcher;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;

class ApiJsonDispatcher extends ApiDispatcher
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function fallback(ApiRequest $request, ApiResponse $response)
	{
		return $response
			->withStatus(404)
			->writeJsonBody(['error' => 'No matched route by given URL']);
	}

}
