<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

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
		$response->setHeader('Content-Type', 'application/json');

		// Encode body
		$body = json_encode($response->getData());
		$response->setBody($body);

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
		$body = json_decode($request->getContents(), TRUE);
		$request->setData($body);

		return $request;
	}

}
