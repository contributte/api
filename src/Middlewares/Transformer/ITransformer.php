<?php

namespace Contributte\Api\Middlewares\Transformer;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;

interface ITransformer
{

	/**
	 * Parse given data from request
	 *
	 * @param ApiRequest $request
	 * @param array $options
	 * @return ApiRequest
	 */
	public function decode(ApiRequest $request, array $options = []);

	/**
	 * Encode given data for response
	 *
	 * @param ApiResponse $response
	 * @param array $options
	 * @return ApiResponse
	 */
	public function encode(ApiResponse $response, array $options = []);

}
