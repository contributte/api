![](https://heatbadger.now.sh/github/readme/contributte/api/)

<p align=center>
  <a href="https://github.com/contributte/api/actions"><img src="https://badgen.net/github/checks/contributte/api/master?cache=300"></a>
  <a href="https://codecov.io/gh/contributte/api"><img src="https://badgen.net/codecov/c/github/contributte/api"></a>
  <a href="https://packagist.org/packages/contributte/api"> <img src="https://badgen.net/packagist/dm/contributte/api"> </a>
  <a href="https://packagist.org/packages/contributte/api"> <img src="https://badgen.net/packagist/v/contributte/api"> </a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/contributte/api"><img src="https://badgen.net/packagist/php/contributte/api"></a>
  <a href="https://github.com/contributte/api"><img src="https://badgen.net/github/license/contributte/api"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/become/a%20patron/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Powerful, documented, validated, built-in API for Nette Framework.

## Versions

| State  | Version | Branch   | Nette | PHP     |
|--------|---------|----------|-------|---------|
| dev    | `^0.5`  | `master` | 3.2+  | `>=8.2` |
| stable | `^0.4`  | `master` | 3.1+  | `>=8.1` |

## Installation

To install the latest version of `contributte/api` use [Composer](https://getcomposer.org).

```
composer require contributte/api
```

## Configuration

### Minimal configuration

NEON configuration:

```neon
extensions:
	api: Contributte\Api\DI\ApiExtension

services:
	- App\Api\HelloController
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

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\PureResponse;
use Contributte\Api\Http\ResponseInterface;

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
		# Catch & handle errors
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

## Examples

There is example project [contributte/api-skeleton](https://github.com/contributte/api-skeleton).

## Development

See [how to contribute](https://contributte.org/contributing.html) to this package.

This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
  <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners.html) **contributte** development team.
Also thank you for using this package.
