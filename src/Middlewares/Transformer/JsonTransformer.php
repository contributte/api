<?php

namespace Contributte\Api\Middlewares\Transformer;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;

class JsonTransformer implements ITransformer
{

	/**
	 * Encode given data for response
	 *
	 * @param ApiResponse $response
	 * @param array $options
	 * @return ApiResponse
	 */
	public function encode(ApiResponse $response, array $options = [])
	{
		// Return immediately if response has no data
		if (!$response->hasData()) return $response;

		// Setup content type
		$response = $response
			->writeJsonBody($response->getData())
			->withHeader('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * Parse given data from request
	 *
	 * @param ApiRequest $request
	 * @param array $options
	 * @return ApiRequest
	 */
	public function decode(ApiRequest $request, array $options = [])
	{
		// Decode request pure JSON
		$request = $request->withParsedBody($request->getJsonBody());

		return $request;
	}

}
