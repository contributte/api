<?php

namespace Contributte\Api\Http\Response;

use Exception;
use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;

final class ExceptionResponse extends JsonableResponse
{

	/** @var Exception */
	private $exception;

	/**
	 * @param Exception $exception
	 */
	public function __construct(Exception $exception)
	{
		$this->exception = $exception;
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 * @return void
	 */
	protected function doJson(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$httpResponse->setCode(500);
		$httpResponse->setExpiration(FALSE);
		$this->json([
			'type' => 'error',
			'message' => $this->exception->getMessage(),
		]);
		$this->close();
	}

}
