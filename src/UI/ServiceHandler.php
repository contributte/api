<?php

namespace Contributte\Api\UI;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
use Nette\DI\Container;

final class ServiceHandler implements IHandler
{

	/** @var Container */
	private $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	public function handle(ApiRequest $request, ApiResponse $response)
	{
		$handler = $request->getEndpoint()->getHandler();

		// Find handler in DI container by class
		$service = $this->container->getByType($handler->getClass());
		$method = $handler->getMethod();

		// Call service::method with ($request, $response) as arguments
		$response = call_user_func_array(
			[$service, $method],
			[$request, $response]
		);

		// Validate if response is returned
		if (!$response) {
			throw new InvalidStateException(sprintf('Handler "%s::%s()" must return response', get_class($service), $method));
		}

		// Validate if response is ApiResponse
		if (!($response instanceof ApiResponse)) {
			throw new InvalidStateException(sprintf('Handler returned response must be subtype of %s', ApiResponse::class));
		}

		return $response;
	}

}
