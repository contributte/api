<?php

namespace Contributte\Api\Bridges\Tracy\Negotiation\Transformer;

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\ITransformer;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Tracy\Debugger;

class DebugTransformer implements ITransformer
{

	/**
	 * @param mixed $data
	 * @param array $options
	 * @return mixed
	 */
	public function encode($data, array $options = [])
	{
		return Debugger::dump($data, TRUE);
	}

	/**
	 * @param mixed $data
	 * @param array $options
	 * @return mixed
	 */
	public function decode($data, array $options = [])
	{
		throw new InvalidStateException('No decode mode');
	}

}
