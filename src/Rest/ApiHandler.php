<?php

namespace Contributte\Api\Rest;

use Nette\DI\Container;
use Contributte\Api\Http\Response\IApiResponse;
use Contributte\Api\Rest\Binding\RequestContext;

final class ApiHandler implements IHandler
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
	 * @param RequestContext $context
	 * @return IApiResponse
	 */
	public function process(RequestContext $context)
	{
		$handler = $context->getEndpoint()->getHandler();

		$controller = $this->container->getByType($handler->getClass());
		$method = $handler->getMethod();

		if ($context->hasParams()) {
			$response = call_user_func_array([$controller, $method], $context->getParams());
		} else {
			$response = call_user_func([$controller, $method]);
		}

		return $response;
	}

}
