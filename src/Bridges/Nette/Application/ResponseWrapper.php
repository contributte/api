<?php

namespace Contributte\Api\Bridges\Nette\Application;

use Nette\Application\IResponse;
use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;
use Contributte\Api\Http\Response\IApiResponse;

class ResponseWrapper implements IResponse
{

	/** @var IApiResponse */
	private $apiResponse;

	/**
	 * @param IApiResponse $apiResponse
	 */
	public function __construct(IApiResponse $apiResponse)
	{
		$this->apiResponse = $apiResponse;
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 */
	public function send(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		// Send our API response. This part of code is required for the independency of nette framework.
		$this->apiResponse->send($httpRequest, $httpResponse);
		exit();
	}

}
