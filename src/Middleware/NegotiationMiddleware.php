<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Formatter\FormatterInterface;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;

class NegotiationMiddleware implements MiddlewareInterface
{

	public function __construct(
		protected FormatterInterface $formatter
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function process(ApiRequest $apiRequest, callable $next): ResponseInterface
	{
		return $this->formatter->format($next($apiRequest));
	}

}
