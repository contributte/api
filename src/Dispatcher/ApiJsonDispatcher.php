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
		$psr7 = $response->getPsr7()->withStatus(404);
		$psr7->getBody()->write(json_encode(['error' => 'No matched route by given URL']));

		return $response->withPsr7($psr7);
	}

}
