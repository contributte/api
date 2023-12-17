<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

class MetadataBag
{

	/** @var array<string, mixed> */
	protected array $data = [];

	public function get(string $name): mixed
	{
		return $this->data[$name] ?? null;
	}

	public function set(string $name, mixed $value): void
	{
		$this->data[$name] = $value;
	}

}
