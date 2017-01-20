<?php

namespace Contributte\Api\UI\Binding;

use Contributte\Api\Schema\Endpoint;

final class RequestContextFactory
{

	/**
	 * @param Endpoint $endpoint
	 * @param array $parameters
	 * @return RequestContext
	 */
	public static function create(Endpoint $endpoint, array $parameters)
	{
		$params = [];

		// Create *<>RequestParam by given parameters and scheme
		foreach ($parameters as $name => $value) {
			$params[$name] = new ScalarRequestParam($name, $value);
		}

		// Create request endpoint
		return new RequestContext($endpoint, $params);
	}

}
