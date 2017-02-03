<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation;

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\IOutTransformer;
use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\ITransformer;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Exception\Logical\InvalidTypeException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiDataResponse;
use Contributte\Api\Http\Response\ApiResponse;

class UrlNegotiator implements IResponseNegotiator
{

	// Masks
	const FALLBACK = '*';

	/** @var IOutTransformer[] */
	private $transformers = [];

	/**
	 * @param IOutTransformer[] $transformers
	 */
	public function __construct(array $transformers)
	{
		$this->transformers = $transformers;
	}

	/**
	 * NEGOTIATION *************************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse|ApiDataResponse $response
	 * @return ApiResponse|ApiDataResponse|NULL
	 */
	public function negotiate(ApiRequest $request, ApiResponse $response)
	{
		if (!($response instanceof ApiDataResponse)) {
			throw new InvalidTypeException(sprintf('Response must be %s', ApiDataResponse::class));
		}

		if (!$this->transformers) {
			throw new InvalidStateException('Please add at least one transformer');
		}

		$psr7Request = $request->getPsr7();
		$path = $psr7Request->getUri()->getPath();

		foreach ($this->transformers as $suffix => $transformer) {
			// Match transformer for the suffix? (.json?)
			if (substr($path, -strlen($suffix)) === $suffix) {
				// Transform data to given format
				return $this->transform($transformer, $request, $response);
			}
		}

		// Try fallback
		if (isset($this->transformers[self::FALLBACK])) {
			// Transform (fallback) data to given format
			return $this->transform($this->transformers[self::FALLBACK], $request, $response);
		}

		return NULL;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param ITransformer $transformer
	 * @param ApiRequest $request
	 * @param ApiDataResponse $response
	 * @return ApiDataResponse
	 */
	protected function transform(ITransformer $transformer, ApiRequest $request, ApiDataResponse $response)
	{
		$transformed = $transformer->encode($response->getData());

		$response
			->getPsr7()
			->getBody()
			->write($transformed);

		return $response;
	}

}
