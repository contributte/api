<?php

namespace Fixtures\Controllers;

use Contributte\Api\Annotation\Controller\Controller;
use Contributte\Api\Annotation\Controller\Method;
use Contributte\Api\Annotation\Controller\Path;
use Contributte\Api\Annotation\Controller\RootPath;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

/**
 * @Controller()
 * @RootPath("/foobar")
 */
final class FoobarController
{

	/**
	 * @Path("/baz")
	 * @Method("GET")
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return void
	 */
	public function actionBaz(ApiRequest $request, ApiResponse $response)
	{
	}

}
