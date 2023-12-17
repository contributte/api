<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

/**
 * @phpstan-consistent-constructor
 */
abstract class EntityResponse extends ApiResponse
{

	/** @var mixed[] */
	protected array $payload = [];

	final public function __construct()
	{
		// Secured constructor
	}

	/**
	 * @return static
	 */
	public static function create(): self
	{
		return new static();
	}

	/**
	 * @return mixed[]
	 */
	public function getPayload(): array
	{
		return $this->payload;
	}

}
