<?php declare(strict_types = 1);

namespace Contributte\Api\Controller\Helpers;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\DataResponse;
use Contributte\Api\Http\ErrorResponse;
use Contributte\Api\Http\ResponseInterface;
use Contributte\Api\Openapi\OpenapiGenerator;
use Throwable;

trait OpenapiEndpoint
{

	private OpenapiGenerator $openapi;

	public function __construct(OpenapiGenerator $openapi)
	{
		$this->openapi = $openapi;
	}

	public function __invoke(ApiRequest $request): ResponseInterface
	{
		try {
			$doc = $this->openapi->document();

			return DataResponse::create()->withDataArray($doc);
		} catch (Throwable $e) {
			return ErrorResponse::create()
				->withErrorCode(500)
				->withMessage($e->getMessage());
		}
	}

}
