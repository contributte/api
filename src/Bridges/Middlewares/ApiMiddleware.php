<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiMiddleware
{

	// Attributes in ServerRequestInterface
	const ATTR_URL = 'C-Api-Url';
	const ATTR_FORMAT = 'C-Api-Format';

	/** @var IInvoker[] */
	protected $invokers;

	/**
	 * @param IInvoker[] $invokers
	 */
	public function __construct(array $invokers)
	{
		$this->invokers = $invokers;
	}

	/**
	 * API - MIDDLEWARE ********************************************************
	 */

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @param callable $next
	 * @return ResponseInterface
	 */
	public function __invoke(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response, callable $next)
	{
		// Process this middleware
		$apiResponse = $this->invoke($psr7Request, $psr7Response);

		// Pass to next middleware
		$psr7Response = $next($psr7Request, $apiResponse->getPsr7());

		return $psr7Response;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiResponse
	 */
	protected function invoke(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		// Create API request & response
		$apiRequest = $this->apiRequest = $this->createApiRequest($psr7Request, $psr7Response);
		$apiResponse = $this->apiResponse = $this->createApiResponse($psr7Request, $psr7Response);

		/** @var IInvoker $invoker */
		$invoker = $this->createChain($this->invokers);

		// Pass this API request/response to API invokers
		$apiResponse = $invoker(
			$apiRequest,
			$apiResponse,
			function (ApiRequest $request, ApiResponse $response) {
				return $response;
			}
		);

		return $apiResponse;
	}

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiRequest
	 */
	protected function createApiRequest(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		return (new ApiRequest())->withPsr7($psr7Request);
	}

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiResponse
	 */
	protected function createApiResponse(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		return (new ApiResponse())->withPsr7($psr7Response);
	}

	/**
	 * @param IInvoker[] $invokers
	 * @return callable
	 */
	protected function createChain(array $invokers)
	{
		// Last invoker
		$next = function (ApiRequest $request, ApiResponse $response) {
			return $response;
		};

		while ($invoker = array_pop($invokers)) {
			$next = function (ApiRequest $request, ApiResponse $response) use ($invoker, $next) {
				return $invoker->invoke($request, $response, $next);
			};
		}

		return $next;
	}

}
