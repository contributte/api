<?php

namespace Contributte\Api\Router;

use Contributte\Api\Http\Request\ApiRequest;

interface IRouter
{

	/**
	 * @param ApiRequest $request
	 * @return ApiRequest|NULL
	 */
	public function match(ApiRequest $request);

}
