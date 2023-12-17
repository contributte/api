<?php declare(strict_types = 1);

namespace Contributte\Api\Controller\Helpers;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\PureResponse;
use Contributte\Api\Http\ResponseInterface;

trait PingEndpoint
{

	public function __invoke(ApiRequest $request): ResponseInterface
	{
		return PureResponse::create()
			->withPayload('pong');
	}

}
