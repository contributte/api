<?php

namespace Contributte\Api\Http\Response;

use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;
use Contributte\Api\Exception\Logical\InvalidTypeException;

abstract class AbstractResponse implements IApiResponse
{

	/** @var int */
	protected $code = 200;

	/** @var string */
	protected $contentType;

	/** @var mixed */
	protected $body;

	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param int $code
	 */
	public function setCode($code)
	{
		$this->code = intval($code);
	}

	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * @param string $contentType
	 */
	public function setContentType($contentType)
	{
		$this->contentType = $contentType;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 */
	public function send(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$httpResponse->setCode($this->code);
		$this->doSend($httpRequest, $httpResponse);
		echo $this->body;
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 */
	abstract protected function doSend(HttpRequest $httpRequest, HttpResponse $httpResponse);

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param string $data
	 * @return void
	 */
	protected function write($data)
	{
		if (!is_scalar($data)) {
			throw new InvalidTypeException(sprintf('Writable data should be scalar type (string,int,float,boolean), [%s] given', gettype($data)));
		}

		$this->body = $data;
	}

	/**
	 * Terminate cycle
	 *
	 * @return void
	 */
	protected function close()
	{
		// Nothing..
	}

}
