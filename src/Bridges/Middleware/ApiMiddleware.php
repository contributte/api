<?php

namespace Contributte\Api\Bridges\Middleware;

use Contributte\Api\Dispatcher\IDispatcher;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiMiddleware
{

	/** @var IDispatcher */
	private $dispatcher;

	/**
	 * @param IDispatcher $dispatcher
	 */
	public function __construct(IDispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @param callable $next
	 * @return ResponseInterface
	 */
	public function __invoke(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response, callable $next)
	{
		// Create API request & response
		$apiRequest = $this->createApiRequest($psr7Request, $psr7Response);
		$apiResponse = $this->createApiResponse($psr7Request, $psr7Response);

		try {
			// Pass to dispatcher, find handler, process some logic and return response.
			$apiResponse = $this->dispatcher->dispatch($apiRequest, $apiResponse);

			// Validate returned api response
			if (!($apiResponse instanceof ApiResponse)) {
				throw new InvalidStateException(sprintf('Returned response must be type of %s', ApiResponse::class));
			}
		} catch (Exception $e) {
			// Just throw this out
			throw $e;
		}

		// Pass to next middleware
		$psr7Response = $next($apiRequest->getRequest(), $apiResponse->getResponse());

		return $psr7Response;
	}

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiRequest
	 */
	protected function createApiRequest(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		return (new ApiRequest())->withRequest($psr7Request);
	}

	/**
	 * @param ServerRequestInterface $psr7Request
	 * @param ResponseInterface $psr7Response
	 * @return ApiResponse
	 */
	protected function createApiResponse(ServerRequestInterface $psr7Request, ResponseInterface $psr7Response)
	{
		return (new ApiResponse())->withResponse($psr7Response);
	}

}
