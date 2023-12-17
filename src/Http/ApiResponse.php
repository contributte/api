<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

abstract class ApiResponse implements ResponseInterface
{

	/** @var array<string, string> */
	protected array $headers = [];

	protected int $statusCode = 200;

	public function getStatusCode(): int
	{
		return $this->statusCode;
	}

	/**
	 * @return array<string, string>
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @return static
	 */
	public function withStatusCode(int $code): self
	{
		$this->statusCode = $code;

		return $this;
	}

	/**
	 * @param array<string, string> $headers
	 * @return static
	 */
	public function withHeaders(array $headers): self
	{
		$this->headers = $headers;

		return $this;
	}

}
