<?php

namespace Contributte\Api\Router;

use Contributte\Api\Http\Request\IApiRequest;
use Contributte\Api\Rest\Binding\RequestContext;

interface IRouter
{

	/**
	 * @param IApiRequest $request
	 * @return RequestContext|NULL
	 */
	public function match(IApiRequest $request);

}
