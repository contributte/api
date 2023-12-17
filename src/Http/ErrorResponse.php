<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

use Throwable;

class ErrorResponse extends ApiResponse
{

	private ?int $errorCode = null;

	private ?string $message = null;

	/** @var mixed[] */
	private array $validations = [];

	private ?Throwable $exception = null;

	private ?ApiRequest $request = null;

	public static function create(): self
	{
		return new self();
	}

	public function getErrorCode(): ?int
	{
		return $this->errorCode;
	}

	public function withErrorCode(int $errorCode): self
	{
		$this->errorCode = $errorCode;

		return $this;
	}

	public function getMessage(): ?string
	{
		return $this->message;
	}

	public function withMessage(?string $message): self
	{
		$this->message = $message ?? 'Something went wrong';

		return $this;
	}

	/**
	 * @return mixed[]
	 */
	public function getValidations(): array
	{
		return $this->validations;
	}

	/**
	 * @param mixed[] $validations
	 */
	public function withValidations(array $validations): self
	{
		$this->validations = $validations;

		return $this;
	}

	public function withException(Throwable $exception): self
	{
		$this->exception = $exception;

		return $this;
	}

	public function getException(): ?Throwable
	{
		return $this->exception;
	}

	public function withRequest(ApiRequest $request): self
	{
		$this->request = $request;

		return $this;
	}

	public function getRequest(): ?ApiRequest
	{
		return $this->request;
	}

	/**
	 * @return array<string, int|string|array<mixed>>
	 */
	public function getPayload(): array
	{
		$output = [];

		if ($this->errorCode !== null) {
			$output['code'] = $this->errorCode;
		}

		if ($this->message !== null) {
			$output['message'] = $this->message;
		}

		if ($this->validations !== []) {
			$output['validations'] = $this->validations;
		}

		return $output;
	}

}
