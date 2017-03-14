<?php

namespace Contributte\Api\Bridges\Tracy\Negotiation\Transformer;

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\ITransformer;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
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
	 * @param ApiResponse $response
	 * @param array $options
	 * @return mixed
	 */
	public function encode(ApiResponse $response, array $options = [])
	{
		Debugger::$maxDepth = $this->maxDepth;
		Debugger::$maxLength = $this->maxLength;

		return Debugger::dump($response->getData(), TRUE);
	}

	/**
	 * @param ApiRequest $request
	 * @param array $options
	 * @return mixed
	 */
	public function decode(ApiRequest $request, array $options = [])
	{
		throw new InvalidStateException('No decode mode');
	}

}
