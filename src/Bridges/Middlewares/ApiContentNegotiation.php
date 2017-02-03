<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Bridges\Middlewares\Negotiation\IRequestNegotiator;
use Contributte\Api\Bridges\Middlewares\Negotiation\IResponseNegotiator;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiDataResponse;
use Contributte\Api\Http\Response\ApiResponse;

class ApiContentNegotiation implements IInvoker
{

	// Attributes in ServerRequestInterface
	const ATTR_SKIP = 'C-Negotiation-Skip';
	const ATTR_SKIP_REQUEST = 'C-Negotiation-Skip-Request';
	const ATTR_SKIP_RESPONSE = 'C-Negotiation-Skip-Response';

	/** @var IRequestNegotiator[] */
	protected $requestNegotiators = [];

	/** @var IResponseNegotiator[] */
	protected $responseNegotiators = [];

	/**
	 * @param IRequestNegotiator[] $requestNegotiators
	 * @param IResponseNegotiator[] $responseNegotiators
	 */
	public function __construct(array $requestNegotiators = [], array $responseNegotiators = [])
	{
		$this->requestNegotiators = $requestNegotiators;
		$this->responseNegotiators = $responseNegotiators;
	}

	/**
	 * SETTERS *****************************************************************
	 */

	/**
	 * @param IRequestNegotiator[] $negotiators
	 * @return void
	 */
	public function addRequestNegotiations(array $negotiators)
	{
		foreach ($negotiators as $negotiator) {
			$this->addRequestNegotiation($negotiator);
		}
	}

	/**
	 * @param IRequestNegotiator $negotiator
	 * @return void
	 */
	public function addRequestNegotiation(IRequestNegotiator $negotiator)
	{
		$this->requestNegotiators[] = $negotiator;
	}

	/**
	 * @param IResponseNegotiator[] $negotiators
	 * @return void
	 */
	public function addResponseNegotiations(array $negotiators)
	{
		foreach ($negotiators as $negotiator) {
			$this->addRequestNegotiation($negotiator);
		}
	}

	/**
	 * @param IResponseNegotiator $negotiator
	 * @return void
	 */
	public function addResponseNegotiation(IResponseNegotiator $negotiator)
	{
		$this->responseNegotiators[] = $negotiator;
	}

	/**
	 * API - INVOKING **********************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse|ApiDataResponse $response
	 * @param callable $next
	 * @return ApiResponse
	 */
	public function invoke(ApiRequest $request, ApiResponse $response, callable $next)
	{
		// Validation
		if (!($request instanceof ApiDataResponse)) {
			throw new InvalidStateException(sprintf('Given API request must be of type %s', ApiDataResponse::class));
		}

		// Should we skip negotiation?
		if ($request->getPsr7()->getAttribute(self::ATTR_SKIP, FALSE) === TRUE) {
			return $next($request, $response);
		}

		// 1) Request negotiation
		if ($request->getPsr7()->getAttribute(self::ATTR_SKIP_REQUEST, FALSE) !== TRUE) {
			$request = $this->negotiateRequest($request, $response);
		}

		// Pass to next invoker
		$response = $next($request, $response);

		// 2) Response negotiation
		if ($request->getPsr7()->getAttribute(self::ATTR_SKIP_RESPONSE, FALSE) !== TRUE) {
			$response = $this->negotiateResponse($request, $response);
		}

		return $response;
	}

	/**
	 * NEGOTIATION *************************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiRequest
	 */
	protected function negotiateRequest(ApiRequest $request, ApiResponse $response)
	{
		if (!$this->requestNegotiators) {
			throw new InvalidStateException('Please add at least one request negotiator');
		}

		foreach ($this->requestNegotiators as $negotiator) {
			// Pass to negotiator and check return value
			$negotiated = $negotiator->negotiate($request, $response);

			// If it's not NULL, we have an ApiRequest
			if ($negotiated !== NULL) {
				return $negotiated;
			}
		}

		return $request;
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function negotiateResponse(ApiRequest $request, ApiResponse $response)
	{
		if (!$this->responseNegotiators) {
			throw new InvalidStateException('Please add at least one response negotiator');
		}

		foreach ($this->responseNegotiators as $negotiator) {
			// Pass to negotiator and check return value
			$negotiated = $negotiator->negotiate($request, $response);

			// If it's not NULL, we have an ApiResponse
			if ($negotiated !== NULL) {
				return $negotiated;
			}
		}

		return $response;
	}

}
