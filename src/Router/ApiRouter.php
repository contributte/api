<?php

namespace Contributte\Api\Router;

use Contributte\Api\Bridges\Middlewares\ApiMiddleware;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Schema\ApiSchema;
use Contributte\Api\Schema\Endpoint;
use Contributte\Api\Utils\Regex;

class ApiRouter implements IRouter
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
		$endpoints = $this->schema->getEndpoints();

		// Iterate over all endpoints
		foreach ($endpoints as $endpoint) {
			$matched = $this->matchEndpoint($endpoint, $request);

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

	/**
	 * @param Endpoint $endpoint
	 * @param ApiRequest $request
	 * @return ApiRequest|NULL
	 */
	protected function matchEndpoint(Endpoint $endpoint, ApiRequest $request)
	{
		// Skip unsupported HTTP method
		if (!$endpoint->hasMethod($request->getMethod())) {
			return NULL;
		}

		// Try match given URL (path) by build pattern
		$request = $this->compareUrl($endpoint, $request);

		return $request;
	}

	/**
	 * @param Endpoint $endpoint
	 * @param ApiRequest $request
	 * @return ApiRequest|NULL
	 */
	protected function compareUrl(Endpoint $endpoint, ApiRequest $request)
	{
		// Parse url from ApiRequest
		$psr7 = $request->getPsr7();

		// If ServerRequestInterface has a right API attribute,
		// then use them as URL address (attribute should be filled in routing phase)
		if ($psr7->getAttribute(ApiMiddleware::ATTR_URL)) {
			$url = $psr7->getAttribute(ApiMiddleware::ATTR_URL);
		} else {
			$url = $request->getUri()->getPath();
		}

		// Url has always slash at the beginning
		// and no trailing slash at the end
		$url = sprintf('/%s', trim($url, '/'));

		// Try to match against the pattern
		$match = Regex::match($url, $endpoint->getPattern());

		// Skip if there's no match
		if ($match === NULL) return NULL;

		// Fill ApiRequest attributes with matched variables
		$request = $request->withAttribute(ApiRequest::ATTR_ROUTER_VARS, $match);

		// Fill ApiRequest parameters with matched variables
		foreach ($endpoint->getParameters() as $param) {
			$request = $request->withParameter($param->getName(), $match[$param->getName()]);
		}

		return $request;
	}

}
