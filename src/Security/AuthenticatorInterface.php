<?php declare(strict_types = 1);

namespace Contributte\Api\Security;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ResponseInterface;

interface AuthenticatorInterface
{

	public function authenticate(ApiRequest $request): ResponseInterface|null;

}
