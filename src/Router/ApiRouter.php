<?php declare(strict_types = 1);

namespace Contributte\Api\Router;

use Nette\Routing\RouteList;

class ApiRouter
{

	protected RouteList|ApiRouter $router;

	protected string $presenter = 'ContributteApi:Middleware';

	protected string $group = 'default';

	protected ?string $path = null;

	protected ?RouteList $inner = null;

	public function __construct(RouteList|ApiRouter $router)
	{
		$this->router = $router;
	}

	public function withPath(string $path): self
	{
		$this->path = $path;

		return $this;
	}

	public function withPresenter(string $presenter): self
	{
		$this->presenter = $presenter;

		return $this;
	}

	/**
	 * @param 'GET'|'POST'|'PUT'|'DELETE' $method
	 * @param class-string $controller
	 */
	public function add(string $method, string $path, string $controller): ApiRoute
	{
		$route = new ApiRoute($method, $path, $this->presenter, $controller);
		$this->getRouter()->add($route);

		return $route;
	}

	protected function getRouter(): RouteList
	{
		if ($this->inner === null) {
			$this->inner = $this->router instanceof ApiRouter ? $this->router->getRouter() : $this->router;

			if ($this->path !== null) {
				$this->inner = $this->inner->withPath($this->path);
			}
		}

		return $this->inner;
	}

}
