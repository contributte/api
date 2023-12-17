<?php declare(strict_types = 1);

namespace Tests\Fixtures\App;

use Contributte\Api\Http\ErrorResponse;
use Contributte\Api\Http\ResponseInterface;
use Contributte\Api\Presenter\DefaultPresenter;
use Nette\Application\Request as AppRequest;
use Nette\Http\Request as HttpRequest;

abstract class ApiV1Presenter extends DefaultPresenter
{

	private const QUERY_AUTH = '_apikey';
	private const HEADER_AUTH = 'X-Api-Key';

	protected function configure(): void
	{
		$this->whitelist = [
			'/_/version',
			'/_/apidoc',
			'/api/v1/ping',
		];
	}

	protected function doAuthenticate(): ResponseInterface|null
	{
		$queryAuth = $this->appRequest->getParameter(self::QUERY_AUTH);
		$headerAuth = $this->httpRequest->getHeader(self::HEADER_AUTH);

		$auth = $queryAuth ?? $headerAuth ?? null;

		if (!$auth) {
			return ErrorResponse::create()
				->withStatusCode(401)
				->withErrorCode(401)
				->withMessage('No auth token given');
		}

		// System credential
		$entity = $auth === 'demo';
		if (!$entity) {
			return ErrorResponse::create()
				->withStatusCode(401)
				->withErrorCode(401)
				->withMessage('Authentication failed, try token=demo');
		}

		return null;
	}

	protected function doHandle(AppRequest $appRequest, HttpRequest $httpRequest): ResponseInterface
	{
		return $this->dispatcher->dispatch($appRequest, $httpRequest);
	}

}
