<?php

namespace Contributte\Api\Router;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Router\Matcher\RegexMatcher;
use Contributte\Api\Schema\ApiSchema;

final class ApiRouter implements IRouter
{

	/** @var ApiSchema */
	private $schema;

	/**
	 * @param ApiSchema $schema
	 */
	public function __construct(ApiSchema $schema)
	{
		$this->schema = $schema;
	}

	/**
	 * @param ApiRequest $request
	 * @return ApiRequest|NULL
	 */
	public function match(ApiRequest $request)
	{
		$matcher = new RegexMatcher();

		// Iterate over all endpoints
		foreach ($this->schema->getEndpoints() as $endpoint) {

			// Skip unsupported HTTP method
			// @idea in_array($endpoint->getMethods()) ??
			if (strtolower($endpoint->getMethod()) !== strtolower($request->getPsr7()->getMethod())) continue;

			// Try match given URL (path) by build pattern
			$matched = $matcher->match($endpoint, $request);

			// Skip if endpoint is not matched
			if ($matched === NULL) continue;

			// If matched is not NULL, returns given ApiRequest
			// with all parsed arguments and data
			$matched = $request->withEndpoint($endpoint);

			return $matched;
		}

		return NULL;
	}

}
