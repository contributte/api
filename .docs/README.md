# Contributte API

Powerful, documented, validated, built-in API to Nette Framework.

## Content

- [Setup](Setup)

## Setup

```bash
composer require contributte/api
```

## Configuration

### Minimal configuration

NEON configration:

```neon
extensions:
	api: Contributte\Api\DI\ApiExtension

services:
	- App\Api\PingController
	- App\Api\PingController
```

Router configuration:

```php
namespace App\Domain\Routing;

use App\Api\HelloController;
use Contributte\Api\Router\ApiRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;

class RouterFactory
{

	public static function create(): Router
	{
		$router = new RouteList();

		$api = (new ApiRouter($router))->withPath('api');
		$api->add('GET', 'hello', HelloController::class);

		$router[] = new Route('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}

}
```

Controller example:

```php
<?php declare(strict_types = 1);

namespace App\Api;

final class HelloController
{

	public function __invoke(ApiRequest $request): ResponseInterface
	{
		return PureResponse::create()->withPayload('Hello!');
	}

}
```

### Advanced configuration

```neon
extensions:
	api: Contributte\Api\DI\ApiExtension

api:
	middlewares:
		# Catch & handler errors
		- Contributte\Api\Middleware\TracyMiddleware(%debugMode%)
		# Format responses
		- Contributte\Api\Middleware\NegotiationMiddleware(
			Contributte\Api\Formatter\MultiFormatter([
				Contributte\Api\Formatter\JsonFormatter()
			])
		)
		# Process authentication & authorization
		- Contributte\Api\Middleware\MatchMiddleware(
			# skip public routes
			skipTags: [public]
			# apply firewall middleware
			middlewares: [
				Contributte\Api\Middleware\FirewallMiddleware(
					Contributte\Api\Security\StaticFirewall([
						foobar: [user: Foo, role: Bar]
					])
				)
			]
		)
		# Process controllers
		- Contributte\Api\Middleware\DispatcherMiddleware

search:
	# Search for controllers defined in app/Api folder
	controllers:
		in: %appDir%/Api
		files: [*Controller.php]
```

### Getting started

## Examples

There is example project [contributte/api-skeleton](https://github.com/contributte/api-skeleton).
