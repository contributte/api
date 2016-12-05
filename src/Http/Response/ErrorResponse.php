<?php

namespace Contributte\Api\Http\Response;

use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;

final class ErrorResponse extends JsonableResponse
{

	/** @var string */
	private $message;

	/**
	 * @param string $message
	 */
	public function __construct($message)
	{
		$this->message = $message;
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 */
	protected function doJson(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$httpResponse->setCode(500);
		$httpResponse->setExpiration(FALSE);
		$this->json([
			'type' => 'error',
			'message' => $this->message,
		]);
		$this->close();
	}

}
