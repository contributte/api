<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

class PureResponse extends ApiResponse
{

	private mixed $payload;

	public static function create(): self
	{
		return new self();
	}

	public function withPayload(mixed $payload): self
	{
		$this->payload = $payload;

		return $this;
	}

	public function getPayload(): mixed
	{
		return $this->payload;
	}

}
