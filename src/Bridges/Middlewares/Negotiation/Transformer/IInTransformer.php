<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

use Contributte\Api\Http\Request\ApiRequest;

interface IInTransformer
{

	/**
	 * Parse given data from request
	 *
	 * @param ApiRequest $request
	 * @param array $options
	 * @return ApiRequest
	 */
	public function decode(ApiRequest $request, array $options = []);

}
