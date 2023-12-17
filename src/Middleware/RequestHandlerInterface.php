<?php declare(strict_types = 1);

namespace Contributte\Api\Middleware;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;

interface RequestHandlerInterface
{

	public function handle(ApiRequest $request): ResponseInterface;

}
