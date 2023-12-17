<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ErrorResponse;
use Contributte\Api\Http\ResponseInterface;
use Tracy\Debugger;

class TracyMiddleware implements MiddlewareInterface
{

	protected bool $debugMode = false;

	public function __construct(bool $debugMode = false)
	{
		$this->debugMode = $debugMode;
	}

	/**
	 * @inheritDoc
	 */
	public function process(ApiRequest $apiRequest, callable $next): ResponseInterface
	{
		$response = $next($apiRequest);

		if ($this->debugMode
			&& $response instanceof ErrorResponse
			&& $response->getException() !== null) {
			Debugger::log($response->getException());
		}

		return $response;
	}

}
