<?php

namespace Contributte\Api\Router;

use Contributte\Api\Http\Request\IApiRequest;
use Contributte\Api\Rest\Binding\RequestContext;
use Contributte\Api\Rest\Binding\RequestContextFactory;
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
	 * @param IApiRequest $request
	 * @return RequestContext|NULL
	 */
	public function match(IApiRequest $request)
	{
		$matcher = new RegexMatcher();

		// Iterate over all endpoints
		foreach ($this->schema->getEndpoints() as $endpoint) {

			// Skip unsupported HTTP method
			if (strtolower($endpoint->getMethod()) !== strtolower($request->getMethod())) continue;

			// Try match given URL (path) by build pattern
			$match = $matcher->match($endpoint, $request);
			if ($match !== NULL) {
				return RequestContextFactory::create($endpoint, $match);
			}
		}

		return NULL;
	}

}
