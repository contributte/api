<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;
use Contributte\Api\Security\FirewallInterface;

class FirewallMiddleware implements MiddlewareInterface
{

	protected FirewallInterface $firewall;

	/** @var array<string> */
	protected array $whitelist = [];

	public function __construct(FirewallInterface $firewall)
	{
		$this->firewall = $firewall;
	}

	/**
	 * @inheritDoc
	 */
	public function process(ApiRequest $apiRequest, callable $next): ResponseInterface
	{
		// Skip authentication for whitelisted endpoints
		if (in_array($apiRequest->getUrl()->getPath(), $this->whitelist, true)) {
			return $next($apiRequest);
		}

		if (($authResponse = $this->firewall->authenticate($apiRequest)) !== null) {
			return $authResponse;
		}

		return $next($apiRequest);
	}

}
