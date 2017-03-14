<?php

namespace Contributte\Api\Dispatcher;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

class ApiJsonDispatcher extends ApiDispatcher
{

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function fallback(ApiRequest $request, ApiResponse $response)
	{
		$response->setStatus(404);
		$response->setBody(json_encode(['error' => 'No matched route by given URL']));

		return $response;
	}

}
