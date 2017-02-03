<?php

namespace Contributte\Api\Router;

use Contributte\Api\Bridges\Middlewares\ApiMiddleware;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Request\Param\ParametersMapper;
use Contributte\Api\Schema\ApiSchema;
use Contributte\Api\Schema\Endpoint;
use Contributte\Api\Utils\Regex;

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
		if (!$endpoint->hasMethod($request->getPsr7()->getMethod())) {
			return NULL;
		}

		// Try match given URL (path) by build pattern
		$request = $this->matchHttp($endpoint, $request);

		return $request;
	}

	/**
	 * @param Endpoint $endpoint
	 * @param ApiRequest $request
	 * @return ApiRequest|NULL
	 */
	protected function matchHttp(Endpoint $endpoint, ApiRequest $request)
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

		var_dump($match);

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
