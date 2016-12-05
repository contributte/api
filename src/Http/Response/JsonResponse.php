<?php

namespace Contributte\Api\Http\Response;

use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;

final class JsonResponse extends JsonableResponse
{

	/** @var mixed */
	private $data;

	/**
	 * @param mixed $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 * @return void
	 */
	protected function doJson(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$httpResponse->setExpiration(FALSE);
		$this->json([
			'type' => 'success',
			'data' => $this->data,
		]);
		$this->close();
	}

}
