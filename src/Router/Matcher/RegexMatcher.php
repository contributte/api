<?php

namespace Contributte\Api\Router\Matcher;

use Contributte\Api\Bridges\Middlewares\ApiMiddleware;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Schema\Endpoint;
use Nette\Utils\Strings;

final class RegexMatcher
{

	/**
	 * @param Endpoint $endpoint
	 * @param ApiRequest $request
	 * @return ApiRequest
	 */
	public function match(Endpoint $endpoint, ApiRequest $request)
	{
		// Parse url from ApiRequest
		$psr7 = $request->getPsr7();

		// If ServerRequestInterface has a right API attribute,
		// then use them as URL address (attribute should be filled in routing phase)
		if ($psr7->getAttribute(ApiMiddleware::ATTR_URL)) {
			$url = $psr7->getAttribute(ApiMiddleware::ATTR_URL);
		} else {
			$url = $request->getPsr7()->getUri()->getPath();
		}

		// Url has always slash at the beginning
		// and no trailing slash at the end
		$url = sprintf('/%s', trim($url, '/'));

		// Try to match againts the pattern
		$match = Strings::match($url, $endpoint->getPattern());

		// Skip no-match
		if ($match === NULL) return NULL;

		// @todo add parameters!
		return $request;
	}

}
