<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;

interface MiddlewareInterface
{

	/**
	 * @param callable(ApiRequest): ResponseInterface $next
	 */
	public function process(ApiRequest $apiRequest, callable $next): ResponseInterface;

}
