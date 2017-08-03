<?php

namespace Contributte\Api\Router;

use Contributte\Api\Http\ApiRequest;

interface IRouter
{

	/**
	 * @param ApiRequest $request
	 * @return ApiRequest|NULL
	 */
	public function match(ApiRequest $request);

}
