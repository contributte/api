<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Http\Response\ApiDataResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiDataMiddleware extends ApiMiddleware
{

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiDataResponse
	 */
	protected function createApiResponse(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		return (new ApiDataResponse())->withPsr7($psr7Response);
	}

}
