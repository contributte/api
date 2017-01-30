<?php

namespace Contributte\Api\Router\Matcher;

use Contributte\Api\Bridges\Middlewares\ApiMiddleware;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Request\Param\ParametersMapper;
use Contributte\Api\Schema\Endpoint;
use Contributte\Api\Utils\Regex;

final class RegexMatcher implements IMatcher
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
		$match = Regex::match($url, $endpoint->getPattern());

		// Skip if there's no match
		if ($match === NULL) return NULL;

		// Fill request parameters from matched URL
		foreach ($endpoint->getParams() as $param) {
			$request->addParameter(
				$param->getName(),
				ParametersMapper::parse($param->getType(), $match[$param->getName()])
			);
		}

		return $request;
	}

}
