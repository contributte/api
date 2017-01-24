<?php

namespace Contributte\Api\Dispatcher;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
use Contributte\Api\Router\IRouter;
use Contributte\Api\UI\IHandler;

final class ApiDispatcher implements IDispatcher
{

	/** @var IRouter */
	private $router;

	/** @var IHandler */
	private $handler;

	/**
	 * @param IRouter $router
	 * @param IHandler $handler
	 */
	public function __construct(IRouter $router, IHandler $handler)
	{
		$this->router = $router;
		$this->handler = $handler;
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	public function dispatch(ApiRequest $request, ApiResponse $response)
	{
		// Try match request to our routes
		$matchedRequest = $this->match($request, $response);

		// If there is no match route <=> endpoint,
		if ($matchedRequest === NULL) {
			return $this->fallback($request, $response);
		}

		// According to matched endpoint, forward to handler
		return $this->handle($matchedRequest, $response);
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiRequest
	 */
	protected function match(ApiRequest $request, ApiResponse $response)
	{
		return $this->router->match($request);
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function handle(ApiRequest $request, ApiResponse $response)
	{
		return $this->handler->handle($request, $response);
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function fallback(ApiRequest $request, ApiResponse $response)
	{
		$psr7 = $response
			->getPsr7()
			->withStatus(404);

		$psr7->getBody()
			->write('No suitable API handler found');

		return $response->withPsr7($psr7);
	}

}
