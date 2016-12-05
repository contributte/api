<?php

namespace Contributte\Api\Http\Response;

use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;

interface IApiResponse
{

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 * @return void
	 */
	public function send(HttpRequest $httpRequest, HttpResponse $httpResponse);

}
