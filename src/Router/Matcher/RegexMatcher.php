<?php

namespace Contributte\Api\Router\Matcher;

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
		$url = $request->getRequest()->getUri()->getPath();

		// Try to match againts the pattern
		$match = Strings::match($url, $endpoint->getPattern());

		// Skip no-match
		if ($match === NULL) return NULL;

		// @todo add parameters!
		return $request;
	}

}
