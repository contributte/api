<?php

namespace Contributte\Api\Bridges\Middlewares;

interface IApiMiddlewareFactory
{

	/**
	 * @return ApiMiddleware
	 */
	public function create();

}
