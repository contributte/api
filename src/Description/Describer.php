<?php declare(strict_types = 1);

namespace Contributte\Api\Description;

class Describer
{

	public const METHOD_GET = 'GET';
	public const METHOD_POST = 'POST';
	public const METHOD_PUT = 'PUT';
	public const METHOD_DELETE = 'DELETE';
	public const METHOD_OPTIONS = 'OPTIONS';

	private string $path;

	/** @var string[] */
	private array $methods = [];

	private string $description;

	public function getPath(): string
	{
		return $this->path;
	}

	public function withPath(string $path): self
	{
		$this->path = $path;

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getMethods(): array
	{
		return $this->methods;
	}

	/**
	 * @param string[] $methods
	 */
	public function withMethods(array $methods): self
	{
		$this->methods = $methods;

		return $this;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function withDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

}
