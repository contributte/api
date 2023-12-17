<?php declare(strict_types = 1);

namespace Contributte\Api\Router;

use Nette\Http\IRequest;
use Nette\Routing\Route;

class ApiRoute extends Route
{

	public string $method;

	public string $controller;

	/** @var array<string> */
	public array $tags = [];

	/**
	 * @param 'GET'|'POST'|'PUT'|'DELETE' $method
	 * @param class-string $controller
	 */
	public function __construct(string $method, string $mask, string $presenter, string $controller)
	{
		parent::__construct($mask, ['presenter' => $presenter]);

		$this->method = $method;
		$this->controller = $controller;
	}

	/**
	 * @param class-string $controller
	 */
	public function controller(string $controller): self
	{
		$this->controller = $controller;

		return $this;
	}

	/**
	 * @param array<string> $tags
	 */
	public function tag(array $tags): self
	{
		$this->tags = $tags;

		return $this;
	}

	/**
	 * @return array{controller: string, httpMethod: string}
	 */
	public function match(IRequest $httpRequest): ?array
	{
		if ($httpRequest->getMethod() !== $this->method) {
			return null;
		}

		$match = parent::match($httpRequest);

		if ($match !== null) {
			$match['controller'] = $this->controller;
			$match['httpMethod'] = $this->method;
			$match['tags'] = $this->tags;
		}

		return $match;
	}

}
