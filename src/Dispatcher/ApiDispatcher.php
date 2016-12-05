<?php

namespace Contributte\Api\Dispatcher;

use Contributte\Api\Http\Request\IApiRequest;
use Contributte\Api\Http\Response\IApiResponse;
use Contributte\Api\Http\Response\SystemResponse;
use Contributte\Api\Rest\IHandler;
use Contributte\Api\Router\IRouter;

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
	 * @param IApiRequest $request
	 * @return IApiResponse
	 */
	public function dispatch(IApiRequest $request)
	{
		// Try match request to our routes
		$match = $this->router->match($request);

		// According to matched endpoint, forward to handler
		if ($match !== NULL) {
			return $this->handler->process($match);
		}

		// @todo Maybe, events, middlewares in here..

		// Otherwise, not found
		return new SystemResponse($request, SystemResponse::NOT_FOUND);
	}

}
