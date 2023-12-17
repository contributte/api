<?php declare(strict_types = 1);

namespace Contributte\Api\Exception;

use Contributte\Api\Http\ResponseInterface;

class ParsingException extends ApiException
{

	public ResponseInterface $response;

	public static function parsingFailed(ResponseInterface $response): self
	{
		$self = new self();
		$self->response = $response;

		return $self;
	}

	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}

}
