<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation\Transformer;

interface ITransformer
{

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function encode($data);


	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function decode($data);

}
