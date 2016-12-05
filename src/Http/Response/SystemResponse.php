<?php

namespace Contributte\Api\Http\Response;

use Contributte\Api\Http\Request\IApiRequest;
use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;
use Nette\InvalidStateException;

final class SystemResponse extends JsonableResponse
{

	// System types
	const NOT_FOUND = 4;

	/** @var IApiRequest */
	private $request;

	/** @var int */
	private $type;

	/**
	 * @param IApiRequest $request
	 * @param int $type
	 */
	public function __construct(IApiRequest $request, $type)
	{
		$this->request = $request;
		$this->type = $type;
	}

	/**
	 * @return IApiRequest
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 * @return void
	 */
	protected function doJson(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		switch ($this->type) {
			case self::NOT_FOUND:
				$this->doNotFound($httpRequest, $httpResponse);
				break;
			default:
				throw new InvalidStateException();
		}
		$this->close();
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 * @return void
	 */
	protected function doNotFound(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$httpResponse->setCode(404);
		$this->json([
			'type' => 'error',
			'message' => 'No route for this request',
		]);
	}

}
