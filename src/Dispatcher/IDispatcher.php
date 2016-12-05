<?php

namespace Contributte\Api\Dispatcher;

use Contributte\Api\Http\Request\IApiRequest;
use Contributte\Api\Http\Response\IApiResponse;

interface IDispatcher
{

	/**
	 * @param IApiRequest $request
	 * @return IApiResponse
	 */
	public function dispatch(IApiRequest $request);

}
