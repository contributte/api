<?php

namespace Contributte\Api\Bridges\Middlewares\Negotiation;

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\IOutTransformer;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiDataResponse;

class SuffixNegotiator implements IResponseNegotiator, IRequestNegotiator
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
		$this->addTransformers($transformers);
	}

	/**
	 * GETTERS/SETTERS *********************************************************
	 */

	/**
	 * @param IOutTransformer[] $transformers
	 * @return void
	 */
	private function addTransformers(array $transformers)
	{
		foreach ($transformers as $suffix => $transformer) {
			$this->addTransformer($suffix, $transformer);
		}
	}

	/**
	 * @param string $suffix
	 * @param IOutTransformer $transformer
	 * @return void
	 */
	private function addTransformer($suffix, IOutTransformer $transformer)
	{
		$this->transformers[$suffix] = $transformer;
	}

	/**
	 * NEGOTIATION *************************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiDataResponse $response
	 * @return ApiRequest|NULL
	 */
	public function negotiateRequest(ApiRequest $request, ApiDataResponse $response)
	{
		if (!$this->transformers) {
			throw new InvalidStateException('Please add at least one transformer');
		}

		$psr7Request = $request->getPsr7();
		$path = $psr7Request->getUri()->getPath();

		foreach ($this->transformers as $suffix => $transformer) {
			// Skip fallback transformer
			if ($suffix == self::FALLBACK) continue;

			// Normalize suffix
			$suffix = sprintf('.%s', ltrim($suffix, '.'));

			// Try match by suffix
			if ($this->match($path, $suffix) === TRUE) {
				// Strip suffix from URL
				$newPath = substr($path, 0, strlen($path) - strlen($suffix));

				// Update ApiRequest without suffix (.json, ...)
				$request = $request->withPsr7(
					$psr7Request->withUri(
						$psr7Request->getUri()->withPath($newPath)
					)
				);

				return $request;
			}
		}

		return NULL;
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiDataResponse $response
	 * @return ApiDataResponse|NULL
	 */
	public function negotiateResponse(ApiRequest $request, ApiDataResponse $response)
	{
		if (!$this->transformers) {
			throw new InvalidStateException('Please add at least one transformer');
		}

		$psr7Request = $request->getPsr7();
		$path = $psr7Request->getUri()->getPath();

		foreach ($this->transformers as $suffix => $transformer) {
			// Skip fallback transformer
			if ($suffix == self::FALLBACK) continue;

			// Normalize suffix
			$suffix = sprintf('.%s', ltrim($suffix, '.'));

			// Try match by suffix
			if ($this->match($path, $suffix) === TRUE) {
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
	 * @param IOutTransformer $transformer
	 * @param ApiRequest $request
	 * @param ApiDataResponse $response
	 * @return ApiDataResponse
	 */
	protected function transform(IOutTransformer $transformer, ApiRequest $request, ApiDataResponse $response)
	{
		$transformed = $transformer->encode($response->getData());

		$response
			->getPsr7()
			->getBody()
			->write($transformed);

		return $response;
	}

	/**
	 * Match transformer for the suffix? (.json?)
	 *
	 * @param string $path
	 * @param string $suffix
	 * @return bool
	 */
	private function match($path, $suffix)
	{
		return substr($path, -strlen($suffix)) === $suffix;
	}

}
