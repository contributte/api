# API - Guidelines

## Content

- [Installation - how to register an extension](#installation)

## Installation

Simpliest way to register this API is via [Nette\DI\CompilerExtension](https://api.nette.org/2.4/Nette.DI.CompilerExtension.html).

```yaml
extensions:
	api: Contributte\Api\Bridges\DI\ApiAnnotationsExtension
```

This is complex example of API configuration with middleware support.

```yaml
extensions:
	api: Contributte\Api\Bridges\DI\ApiAnnotationsExtension
	middlewares: Contributte\Middlewares\DI\NetteMiddlewareExtension

services:
	# Middlewares
	middleware.tracy: Contributte\Middlewares\Middleware\TracyMiddleware
	middleware.basepath: Contributte\Middlewares\Middleware\AutoBasePathMiddleware
	middleware.router: Contributte\Middlewares\Middleware\RouterMiddleware([
		"^/api/{path:.+}": @middleware.api,
		"^/{path:.*}": @middleware.presenter
	])

	# Case #1 (handle API request)[api/]
	middleware.api:
		class: Contributte\Api\Bridges\Middlewares\ApiDataMiddleware([
			Contributte\Api\Bridges\Middlewares\ApiRouter("^/api/{api}{?format:\\.json|debug}"),
			Contributte\Api\Bridges\Middlewares\ApiContentNegotiation([
				Contributte\Api\Bridges\Middlewares\Negotiation\UrlNegotiator([
					"json": Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\JsonTransformer(),
					"debug": Contributte\Api\Bridges\Tracy\Negotiation\Transformer\DebugTransformer(),
					"*": Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\JsonTransformer()
				])
			]),
			Contributte\Api\Bridges\Middlewares\ApiEmitter()
		])

	# Case #2 (handle classic nette application)
	middleware.presenter:
		class: Contributte\Middlewares\Middleware\PresenterMiddleware
		setup:
			- setErrorPresenter(Error)
			- setCatchExceptions(FALSE)

	- {class: App\Middlewares\RootMiddleware, inject: true}

middlewares:
	middlewares:
		- @middleware.tracy
		- @middleware.basepath
		- @middleware.router
```
