<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

use LogicException;
use Nette\Application\Response;
use Nette\Http\IRequest as HttpRequest;
use Nette\Http\IResponse as HttpResponse;

class NetteResponse implements Response
{

	private int $statusCode = 200;

	/** @var array<string, string> */
	private array $headers = [];

	private string $payload;

	public static function create(): self
	{
		return new self();
	}

	public static function of(ResponseInterface $result): self
	{
		if (!is_scalar($result->getPayload())) {
			throw new LogicException('Payload must be scalar');
		}

		$self = new self();
		$self->withStatusCode($result->getStatusCode());
		$self->withPayload($result->getPayload()); // @phpstan-ignore-line
		$self->withHeaders($result->getHeaders());

		return $self;
	}

	public function withStatusCode(int $statusCode): self
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * @param array<string, string> $headers
	 */
	public function withHeaders(array $headers): self
	{
		$this->headers = $headers;

		return $this;
	}

	public function withPayload(string $payload): self
	{
		$this->payload = $payload;

		return $this;
	}

	public function send(HttpRequest $httpRequest, HttpResponse $httpResponse): void
	{
		$httpResponse->setCode($this->statusCode);

		foreach ($this->headers as $header => $value) {
			$httpResponse->setHeader($header, $value);
		}

		echo $this->payload;
	}

}
