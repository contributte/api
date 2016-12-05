<?php

namespace Contributte\Api\Http\Response;

use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;

final class TextResponse extends AbstractResponse
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
	 */
	protected function doSend(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$this->write($this->data);
		$this->close();
	}

}
