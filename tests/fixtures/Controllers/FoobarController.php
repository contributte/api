<?php

namespace Fixtures\Controllers;

use Contributte\Api\Annotation\Controller\Controller;
use Contributte\Api\Annotation\Controller\Method;
use Contributte\Api\Annotation\Controller\Path;
use Contributte\Api\Annotation\Controller\RootPath;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;
use Contributte\Api\UI\Controller\IController;

/**
 * @Controller()
 * @RootPath("/foobar")
 */
final class FoobarController implements IController
{

	/**
	 * @Path("/baz1")
	 * @Method("GET")
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return void
	 */
	public function baz1(ApiRequest $request, ApiResponse $response)
	{
	}

	/**
	 * @Path("/baz2")
	 * @Method({"GET", "POST"})
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return void
	 */
	public function baz2(ApiRequest $request, ApiResponse $response)
	{
	}

	/**
	 * @Path("/baz2")
	 * @Method(methods={"PUT"})
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return void
	 */
	public function baz3(ApiRequest $request, ApiResponse $response)
	{
	}

	/**
	 * @return void
	 */
	public function getData1()
	{
		// Skip this method
	}

	/**
	 * @return void
	 */
	protected function getData2()
	{
		// Skip this method
	}

	/**
	 * @return void
	 */
	protected function getData3()
	{
		// Skip this method
	}

}
