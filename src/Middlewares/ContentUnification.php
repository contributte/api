<?php

namespace Contributte\Api\Middlewares;

use Contributte\Api\Exception\Api\ClientErrorException;
use Contributte\Api\Exception\Api\ServerErrorException;
use Exception;
use Tlapnet\Api\Http\ApiRequest;
use Tlapnet\Api\Http\ApiResponse;

/**
 * @see https://labs.omniti.com/labs/jsend
 */
class ContentUnification
{

	// Attributes in ServerRequestInterface
	const ATTR_SKIP_UNIFICATION = 'C-Unification-Skip';

	// Status codes
	const DEFAULT_SUCCESS_CODE = 200;
	const DEFAULT_CLIENT_ERROR_CODE = 400;
	const DEFAULT_SERVER_ERROR_CODE = 500;
	const DEFAULT_EXCEPTION_CODE = 505;

	// Statuses
	const STATUS_SUCCESS = 'success';
	const STATUS_ERROR = 'error';

	/**
	 * API - MIDDLEWARE ********************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @param callable $next
	 * @return ApiResponse
	 */
	public function __invoke(ApiRequest $request, ApiResponse $response, callable $next)
	{
		try {
			// Pass to next middleware
			$response = $next($request, $response);

			return $this->processSuccess($request, $response);
		} catch (ClientErrorException $e) {
			return $this->processClientError($request, $response, $e);
		} catch (ServerErrorException $e) {
			return $this->processServerError($request, $response, $e);
		} catch (Exception $e) {
			return $this->processException($request, $response, $e);
		}
	}

	/**
	 * PROCESSING **************************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function processSuccess(ApiRequest $request, ApiResponse $response)
	{
		if (!$response->getStatusCode()) {
			$response = $response->withStatus(self::DEFAULT_SUCCESS_CODE);
		}

		// Skip processing if attribute is given
		if ($response->hasAttribute(self::ATTR_SKIP_UNIFICATION)) return $response;

		// Skip processing if unified data not provided
		if (!$response->hasData()) return $response;

		// Setup status code only if it's not set already
		return $response->withData([
			'status' => self::STATUS_SUCCESS,
			'data' => $response->getData(),
		]);
	}


	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @param ClientErrorException $exception
	 * @return ApiResponse
	 */
	protected function processClientError(ApiRequest $request, ApiResponse $response, ClientErrorException $exception)
	{
		// Skip processing if attribute is given
		if ($response->hasAttribute(self::ATTR_SKIP_UNIFICATION)) return $response;

		// Analyze status code
		$code = $exception->getCode();
		$code = $code < 400 || $code > 500 ? self::DEFAULT_CLIENT_ERROR_CODE : $code;

		return $response
			->withStatus($code)
			->withData([
				'status' => self::STATUS_ERROR,
				'data' => $exception->getContext(),
			]);
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @param ServerErrorException $exception
	 * @return ApiResponse
	 */
	protected function processServerError(ApiRequest $request, ApiResponse $response, ServerErrorException $exception)
	{
		// Skip processing if attribute is given
		if ($response->hasAttribute(self::ATTR_SKIP_UNIFICATION)) return $response;

		// Analyze status code
		$code = $exception->getCode();
		$code = $code < 500 || $code > 600 ? self::DEFAULT_SERVER_ERROR_CODE : $code;

		return $response
			->withStatus($code)
			->withData([
				'status' => self::STATUS_ERROR,
				'message' => $exception->getMessage(),
			]);
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @param Exception $exception
	 * @return ApiResponse
	 */
	protected function processException(ApiRequest $request, ApiResponse $response, Exception $exception)
	{
		// Skip processing if attribute is given
		if ($response->hasAttribute(self::ATTR_SKIP_UNIFICATION)) return $response;

		// Analyze status code
		$code = $exception->getCode();
		$code = $code < 400 || $code > 600 ? self::DEFAULT_EXCEPTION_CODE : $code;

		return $response
			->withStatus($code)
			->withData([
				'status' => self::STATUS_ERROR,
				'message' => $exception->getMessage(),
			]);
	}

}
