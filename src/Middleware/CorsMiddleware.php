<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Description\Describer;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\PureResponse;
use Contributte\Api\Http\ResponseInterface;

class CorsMiddleware implements MiddlewareInterface
{

	/**
	 * @param array<string> $needTags
	 */
	public function __construct(
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

		return PureResponse::create()->withHeaders([
			'Access-Control-Allow-Origin' => '*',
			'Access-Control-Allow-Methods' => '*',
			'Access-Control-Allow-Headers' => '*',
		]);
	}

	protected function shouldPass(ApiRequest $apiRequest): bool
	{
		// Other than OPTIONS method
		if ($apiRequest->getMethod() !== Describer::METHOD_OPTIONS) {
			return true;
		}

		// Any of needed tags is present
		return array_intersect((array) $apiRequest->getParameter('tags'), $this->needTags) === [];
	}

}
