<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;

class MiddlewareRunner
{

	/** @var array<int, MiddlewareInterface> */
	private array $middlewares = [];

	/**
	 * @param array<int, MiddlewareInterface> $middlewares
	 */
	public function __construct(array $middlewares = [])
	{
		$this->middlewares = $middlewares;
	}

	public function add(MiddlewareInterface $middleware): void
	{
		$this->middlewares[] = $middleware;
	}

	public function run(ApiRequest $apiRequest): ResponseInterface
	{
		// Build middlewares chain
		$runner = MiddlewareBuilder::of($this->middlewares)->build();

		return $runner($apiRequest);
	}

}
