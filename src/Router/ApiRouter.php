<?php

namespace Contributte\Api\Router;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Router\Matcher\IMatcher;
use Contributte\Api\Router\Matcher\RegexMatcher;
use Contributte\Api\Schema\ApiSchema;

final class ApiRouter implements IRouter
{

	/** @var ApiSchema */
	private $schema;

	/** @var IMatcher */
	private $matcher;

	/**
	 * @param ApiSchema $schema
	 */
	public function __construct(ApiSchema $schema)
	{
		$this->schema = $schema;
	}

	/**
	 * @return IMatcher
	 */
	public function getMatcher()
	{
		if (!$this->matcher) {
			$this->matcher = new RegexMatcher();
		}

		return $this->matcher;
	}

	/**
	 * @param ApiRequest $request
	 * @return ApiRequest|NULL
	 */
	public function match(ApiRequest $request)
	{
		$matcher = $this->getMatcher();

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
			// with all parsed arguments and data,
			// also append given Endpoint
			$matched = $matched->withEndpoint($endpoint);

			return $matched;
		}

		return NULL;
	}

}
