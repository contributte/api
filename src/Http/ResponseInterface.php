<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

interface ResponseInterface
{

	/**
	 * @return array<string, string>
	 */
	public function getHeaders(): array;

	public function getStatusCode(): int;

	public function getPayload(): mixed;

}
