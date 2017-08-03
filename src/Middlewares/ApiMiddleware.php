<?php

namespace Contributte\Api\Middlewares;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;
use Contributte\Middlewares\Utils\ChainBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiMiddleware
{

	/** @var array */
	protected $middlewares = [];

	/**
	 * @param array $middlewares
	 */
	public function __construct(array $middlewares)
	{
		$this->middlewares = $middlewares;
	}

	/**
	 * MIDDLEWARE **************************************************************
	 */

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param callable $next
	 * @return ApiResponse
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
	{
		// Create API request & response
		$apiRequest = $this->createApiRequest($request, $response);
		$apiResponse = $this->createApiResponse($request, $response);

		// Create chain of middlewares
		$chain = ChainBuilder::factory($this->middlewares);

		// Pass request & response to API internal middlewares (ContentNegotiation, Emitter, etc..)
		$apiResponse = $chain($apiRequest, $apiResponse);

		// Pass request & response to next middleware
		$apiResponse = $next($apiRequest, $apiResponse);

		return $apiResponse;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiRequest
	 */
	protected function createApiRequest(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		if ($psr7Request instanceof ApiRequest) return $psr7Request;

		return ApiRequest::of($psr7Request);
	}

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiResponse
	 */
	protected function createApiResponse(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		if ($psr7Response instanceof ApiResponse) return $psr7Response;

		return ApiResponse::of($psr7Response);
	}

}
