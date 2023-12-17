<?php declare(strict_types = 1);

namespace Contributte\Api\Security;

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ErrorResponse;
use Contributte\Api\Http\Metadata;
use Contributte\Api\Http\ResponseInterface;

class StaticFirewall implements FirewallInterface
{

	private const QUERY_KEY = '_apikey';
	private const HEADER_KEY = 'X-Api-Key';

	/** @var array<string, mixed> */
	protected array $auth = [];

	/**
	 * @param array<string, mixed> $auth
	 */
	public function __construct(array $auth)
	{
		$this->auth = $auth;
	}

	public function authenticate(ApiRequest $request): ResponseInterface|null
	{
		$queryKey = $request->getParameter(self::QUERY_KEY);
		$headerKey = $request->getHeader(self::HEADER_KEY);

		$key = $queryKey ?? $headerKey ?? null;

		if ($key === '') {
			return ErrorResponse::create()
				->withStatusCode(401)
				->withErrorCode(401)
				->withMessage('No API token given');
		}

		$auth = $this->auth[$key] ?? null;
		if ($auth === null) {
			return ErrorResponse::create()
				->withStatusCode(401)
				->withErrorCode(401)
				->withMessage('Invalid API token given');
		}

		$request->getMetadata()->set(Metadata::SECURITY_AUTH, $auth);

		return null;
	}

	public function authorize(ApiRequest $request): ResponseInterface|null
	{
		return null;
	}

}
