<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Bridges\Middlewares\Negotiation\INegotiator;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiDataResponse;
use Contributte\Api\Http\Response\ApiResponse;

class ApiContentNegotiation implements IInvoker
{

	/** @var INegotiator[] */
	protected $negotiators = [];

	/**
	 * @param INegotiator[] $negotiators
	 */
	public function __construct(array $negotiators)
	{
		$this->negotiators = $negotiators;
	}

	/**
	 * API - INVOKING **********************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse|ApiDataResponse $response
	 * @param callable $next
	 * @return ApiResponse
	 */
	public function invoke(ApiRequest $request, ApiResponse $response, callable $next)
	{
		// Pass to next invoker
		$response = $next($request, $response);

		// If the response is not a appropriate type of or
		// does not have a any data, return response immediately
		if (!($response instanceof ApiDataResponse) ||
			!$response->isEmpty()
		) {
			return $response;
		}

		// Otherwise, pass to negotiator
		return $this->negotiate($request, $response);
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function negotiate(ApiRequest $request, ApiResponse $response)
	{
		if (!$this->negotiators) {
			throw new InvalidStateException('Please add at least one negotiator');
		}

		foreach ($this->negotiators as $negotiator) {
			// Pass to negotiator and check return value
			$negotiated = $negotiator->negotiate($request, $response);

			// If it's not NULL, we have an ApiResponse
			if ($negotiated !== NULL) {
				return $negotiated;
			}
		}

		return $response;
	}

}
