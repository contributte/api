<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Closure;
use Contributte\Api\Exception\ApiException;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;

class MiddlewareBuilder
{

	/** @var array<int, MiddlewareInterface> */
	private array $middlewares = [];

	private Closure $leaf;

	/**
	 * @param array<int, MiddlewareInterface> $middlewares
	 */
	public function __construct(array $middlewares = [])
	{
		$this->middlewares = $middlewares;
		$this->leaf = fn (ApiRequest $request): ResponseInterface => throw new ApiException('No next middleware');
	}

	/**
	 * @param array<int, MiddlewareInterface> $middlewares
	 */
	public static function of(array $middlewares): self
	{
		return new self($middlewares);
	}

	public function leaf(Closure $closure): self
	{
		$this->leaf = $closure;

		return $this;
	}

	public function build(): Closure
	{
		$middlewares = $this->middlewares;

		if ($middlewares === []) {
			throw new ApiException('At least one middleware is needed');
		}

		$next = $this->leaf;
		while ($middleware = array_pop($middlewares)) {
			$next = fn (ApiRequest $request): ResponseInterface => $middleware->process($request, $next);
		}

		return $next;
	}

}
