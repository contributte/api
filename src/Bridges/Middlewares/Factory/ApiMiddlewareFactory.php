<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Dispatcher\IDispatcher;

class ApiMiddlewareFactory implements IApiMiddlewareFactory
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
	 * @return ApiMiddleware
	 */
	public function create()
	{
		return new ApiMiddleware($this->dispatcher);
	}

}
