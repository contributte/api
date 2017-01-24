<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

class JsonTransformer implements ITransformer
{

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function encode($data)
	{
		return json_encode($data);
	}


	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function decode($data)
	{
		return json_decode($data, TRUE);
	}

}
