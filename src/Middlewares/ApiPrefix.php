<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

class ApiPrefix
{

	// Attributes in ServerRequestInterface
	const ATTR_URL = 'C-Api-Prefix';

	/** @var string */
	protected $prefix;

	/**
	 * @param string $prefix
	 */
	public function __construct($prefix)
	{
		$this->prefix = sprintf('/%s', trim($prefix, '/'));
	}

	/**
	 * API - INVOKING **********************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @param callable $next
	 * @return ApiResponse
	 */
	public function __invoke(ApiRequest $request, ApiResponse $response, callable $next)
	{
		$psr7Request = $request->getPsr7();
		$uri = $psr7Request->getUri();

		// Does URL path start with given API prefix?
		if (strncmp($uri->getPath(), $this->prefix, strlen($this->prefix)) === 0) {
			$newPath = str_replace($this->prefix, NULL, $uri->getPath());

			// Update request with path without prefix
			$request = $request->withPsr7(
				$psr7Request->withUri(
					$psr7Request->getUri()->withPath($newPath)
				)
			);
		}

		// Pass to next API middleware
		return $next($request, $response);
	}

}
