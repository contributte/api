<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

class JsonTransformer implements ITransformer
{

	/**
	 * Encode given data for response
	 *
	 * @param mixed $data
	 * @param array $options
	 * @return mixed
	 */
	public function encode($data, array $options = [])
	{
		return json_encode($data);
	}


	/**
	 * Parse given data from request
	 *
	 * @param mixed $data
	 * @param array $options
	 * @return mixed
	 */
	public function decode($data, array $options = [])
	{
		return json_decode($data, TRUE);
	}

}
