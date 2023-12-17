<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;

class MatchMiddleware implements MiddlewareInterface
{

	/**
	 * @param array<int, MiddlewareInterface> $middlewares
	 * @param array<string> $skipTags
	 * @param array<string> $needTags
	 */
	public function __construct(
		protected array $middlewares = [],
		protected array $skipTags = [],
		protected array $needTags = []
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function process(ApiRequest $apiRequest, callable $next): ResponseInterface
	{
		if ($this->shouldPass($apiRequest)) {
			return $next($apiRequest);
		}

		// Build middlewares chain
		$runner = MiddlewareBuilder::of($this->middlewares)
			->leaf(fn (ApiRequest $request): ResponseInterface => $next($request))
			->build();

		return $runner($apiRequest);
	}

	protected function shouldPass(ApiRequest $apiRequest): bool
	{
		// No defined middlewares
		if ($this->middlewares === []) {
			return true;
		}

		// Skip if request has skipped tag
		return array_intersect((array) $apiRequest->getParameter('tags'), $this->skipTags) !== [];
	}

}
