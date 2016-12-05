<?php

namespace Contributte\Api\Http\Response;

use Nette\Application\BadRequestException;
use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;

final class FileResponse extends AbstractResponse
{

	/** @var string */
	private $file;

	/** @var string */
	private $name;

	/** @var boolean */
	private $forceDownload;

	/**
	 * @param string $file
	 * @param string $name
	 * @param string $contentType
	 * @param bool $forceDownload
	 */
	public function __construct($file, $name = NULL, $contentType = NULL, $forceDownload = TRUE)
	{
		if (!is_file($file)) {
			throw new BadRequestException("File '$file' doesn't exist.");
		}

		$this->file = $file;
		$this->name = $name ? $name : basename($file);
		$this->setContentType($contentType ? $contentType : 'application/octet-stream');
		$this->forceDownload = $forceDownload;
	}

	/**
	 * @param HttpRequest $httpRequest
	 * @param HttpResponse $httpResponse
	 */
	protected function doSend(HttpRequest $httpRequest, HttpResponse $httpResponse)
	{
		$httpResponse->setContentType($this->contentType);
		$httpResponse->setHeader('Content-Disposition',
			($this->forceDownload ? 'attachment' : 'inline')
			. '; filename="' . $this->name . '"'
			. '; filename*=utf-8\'\'' . rawurlencode($this->name));

		$length = filesize($this->file);
		$handle = fopen($this->file, 'r');

		$httpResponse->setHeader('Content-Length', $length);
		while (!feof($handle) && $length > 0) {
			echo $s = fread($handle, min(4e6, $length));
			$length -= strlen($s);
		}
		fclose($handle);
		$this->close();
	}

}
