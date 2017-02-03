<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

interface IOutTransformer
{

	/**
	 * Encode given data for response
	 *
	 * @param mixed $data
	 * @param array $options
	 * @return mixed
	 */
	public function encode($data, array $options = []);

}
