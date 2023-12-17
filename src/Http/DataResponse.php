<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

use Nette\Utils\Validators;
use stdClass;

class DataResponse extends ApiResponse
{

	private mixed $data;

	public static function create(): self
	{
		return new self();
	}

	public function withDataScalar(string|int|float $data): self
	{
		Validators::assert($data, 'scalar');

		$this->data = $data;

		return $this;
	}

	public function withDataStructure(stdClass $data): self
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @param array<mixed> $data
	 */
	public function withDataArray(array $data): self
	{
		$this->data = $data;

		return $this;
	}

	public function getData(): mixed
	{
		return $this->data;
	}

	public function getPayload(): mixed
	{
		return $this->getData();
	}

}
