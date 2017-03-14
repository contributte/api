<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

use Contributte\Api\Http\Response\ApiResponse;

interface IOutTransformer
{

	/**
	 * Encode given data for response
	 *
	 * @param ApiResponse $response
	 * @param array $options
	 * @return ApiResponse
	 */
	public function encode(ApiResponse $response, array $options = []);

}
