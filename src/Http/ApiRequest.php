<?php declare(strict_types = 1);

namespace Contributte\Api\Http;

use Nette\Application\Request as AppRequest;
use Nette\Http\IRequest as HttpRequest;
use Nette\Http\UrlScript;
use Nette\Utils\Json;

/**
 * @phpstan-consistent-constructor
 */
class ApiRequest
{

	private AppRequest $appRequest;

	private HttpRequest $httpRequest;

	private MetadataBag $metadata;

	private function __construct(AppRequest $appRequest, HttpRequest $httpRequest)
	{
		$this->appRequest = $appRequest;
		$this->httpRequest = $httpRequest;
		$this->metadata = new MetadataBag();
	}

	public static function of(AppRequest $appRequest, HttpRequest $httpRequest): self
	{
		return new static($appRequest, $httpRequest);
	}

	public function getUrl(): UrlScript
	{
		return $this->httpRequest->getUrl();
	}

	public function getMethod(): string
	{
		return $this->httpRequest->getMethod();
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getQuery(): array
	{
		return $this->httpRequest->getQuery(); // @phpstan-ignore-line
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getParameters(): array
	{
		return $this->appRequest->getParameters();
	}

	public function getParameter(string $key, mixed $default = null): mixed
	{
		return $this->appRequest->getParameter($key) ?? $default;
	}

	public function getBody(): string|null
	{
		return $this->httpRequest->getRawBody();
	}

	public function getJsonBody(): mixed
	{
		return Json::decode($this->getBody() ?? '');
	}

	public function getHeader(string $header): ?string
	{
		return $this->httpRequest->getHeader($header);
	}

	public function getMetadata(): MetadataBag
	{
		return $this->metadata;
	}

}
