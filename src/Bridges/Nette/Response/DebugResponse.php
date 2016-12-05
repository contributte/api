<?php

namespace Contributte\Api\Bridges\Nette\Response;

use Contributte\Api\Http\Response\AbstractResponse;
use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;
use Tracy\Debugger;

final class DebugResponse extends AbstractResponse
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
	protected function doSend(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$this->write(Debugger::dump($this->data, TRUE));
	}

}
