<?php declare(strict_types = 1);

namespace Contributte\Api\Presenter;

use Contributte\Api\Exception\DispatcherException;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ErrorResponse;
use Contributte\Api\Http\NetteResponse;
use Contributte\Api\Http\ResponseInterface;
use Contributte\Api\Middleware\MiddlewareRunner;
use Nette\Application\IPresenter;
use Nette\Application\Request as AppRequest;
use Nette\Http\Request as HttpRequest;
use Throwable;

class MiddlewarePresenter implements IPresenter
{

	protected HttpRequest $httpRequest;

	protected MiddlewareRunner $runner;

	public function __construct(HttpRequest $httpRequest, MiddlewareRunner $runner)
	{
		$this->httpRequest = $httpRequest;
		$this->runner = $runner;
	}

	public function run(AppRequest $appRequest): NetteResponse
	{
		$apiRequest = ApiRequest::of($appRequest, $this->httpRequest);

		try {
			$response = $this->runner->run($apiRequest);

			return $this->send($response);
		} catch (DispatcherException $e) {
			return $this->send(
				ErrorResponse::create()
					->withRequest($apiRequest)
					->withException($e)
					->withStatusCode(500)
					->withErrorCode(500)
					->withMessage($e->getMessage())
			);
		} catch (Throwable $e) {
			return $this->send(
				ErrorResponse::create()
					->withRequest($apiRequest)
					->withException($e)
					->withStatusCode(500)
					->withErrorCode(500)
					->withMessage('Internal server error')
			);
		}
	}

	protected function send(ResponseInterface $response): NetteResponse
	{
		return NetteResponse::of($response);
	}

}
