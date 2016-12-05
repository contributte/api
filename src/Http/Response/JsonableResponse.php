<?php

namespace Contributte\Api\Http\Response;

use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;
use Nette\Utils\Json;

abstract class JsonableResponse extends AbstractResponse
{

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 */
	protected function doSend(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$httpResponse->setContentType('application/json', 'utf-8');
		$this->doJson($httpRequest, $httpResponse);
		$this->close();
	}

	/**
	 * @param array $data
	 * @return void
	 */
	protected function json(array $data)
	{
		$this->write(Json::encode($data));
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 */
	abstract protected function doJson(HttpRequest $httpRequest, HttpResponse $httpResponse);

}
