<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

interface IInTransformer
{

	/**
	 * Parse given data from request
	 *
	 * @param mixed $data
	 * @param array $options
	 * @return mixed
	 */
	public function decode($data, array $options = []);

}
