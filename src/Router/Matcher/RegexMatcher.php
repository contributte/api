<?php

namespace Contributte\Api\Router\Matcher;

use Nette\Utils\Strings;
use Contributte\Api\Http\Request\IApiRequest;
use Contributte\Api\Schema\Endpoint;

final class RegexMatcher
{

	/**
	 * @param Endpoint $endpoint
	 * @param IApiRequest $request
	 * @return array
	 */
	public function match(Endpoint $endpoint, IApiRequest $request)
	{
		$match = Strings::match($request->getPath(), $endpoint->getPattern());
		$params = [];

		// Skip no-match
		if ($match === NULL) return NULL;

		// Collect request parameters (parsed from regex)
		foreach ($match as $name => $value) {
			if ($endpoint->hasParam($name)) {
				$params[$name] = $value;
			}
		}

		return $params;
	}

}
