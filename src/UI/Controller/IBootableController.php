<?php

namespace Contributte\Api\UI\Controller;

use Contributte\Api\Http\Request\IApiRequest;

interface IBootableController
{

	/**
	 * @param IApiRequest $request
	 * @return void
	 */
	public function boot(IApiRequest $request);

}
