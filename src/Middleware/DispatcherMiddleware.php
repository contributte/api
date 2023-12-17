<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;
use Nette\DI\Container;

class DispatcherMiddleware implements MiddlewareInterface
{

	private Container $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @inheritDoc
	 */
	public function process(ApiRequest $apiRequest, callable $next): ResponseInterface
	{
		/** @var class-string $controllerClass */
		$controllerClass = $apiRequest->getParameter('controller');
		$controller = $this->container->getByType($controllerClass);

		return $controller($apiRequest);
	}

}
