<?php

namespace Contributte\Api\Bridges\Tracy\Negotiation\Transformer;

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\ITransformer;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Tracy\Debugger;

class DebugTransformer implements ITransformer
{

	/** @var int */
	private $maxDepth;

	/** @var int */
	private $maxLength;

	/**
	 * @param int $maxDepth
	 * @param int $maxLength
	 */
	public function __construct($maxDepth = 10, $maxLength = 1500)
	{
		$this->maxDepth = $maxDepth;
		$this->maxLength = $maxLength;
	}

	/**
	 * @param mixed $data
	 * @param array $options
	 * @return mixed
	 */
	public function encode($data, array $options = [])
	{
		Debugger::$maxDepth = $this->maxDepth;
		Debugger::$maxLength = $this->maxLength;

		return Debugger::dump($data, TRUE);
	}

	/**
	 * @param mixed $request
	 * @param array $options
	 * @return mixed
	 */
	public function decode($request, array $options = [])
	{
		throw new InvalidStateException('No decode mode');
	}

}
