# API - Guidelines

## Content

- [Installation - how to register an extension](#installation)
- [Middlewares - how to setup middlewares](#middlewares)
- [Bridge - how use API & middlewares easily](#bridge)
- [Usage - controller showtime](#usage)
- [Advanced - complex configuration](#advanced)
- [Playground - real examples](#playground)

## Installation

Simpliest way to register this API is via [Nette\DI\CompilerExtension](https://api.nette.org/2.4/Nette.DI.CompilerExtension.html).

```
composer require contributte/api
```

```yaml
extensions:
    api: Contributte\Api\DI\ApiExtension
```

## Middlewares

This API is mainly based on [contributte/middlewares](https://github.com/contributte/middlewares). You should register also middleware extension.

```
composer require contributte/middlewares
```

```yaml
extensions:
    middlewares: Contributte\Middlewares\DI\MiddlewaresExtension
```

## Bridge

There is a bridge between API & Middlewares configuration, because you can use each one differently.

```
    api: Contributte\Api\DI\ApiExtension
    api2middlewares: Contributte\Api\DI\Api2MiddlewaresExtension
    middlewares: Contributte\Middlewares\DI\MiddlewaresExtension
```

## Usage

### Controllers

Your job is to create a couple of controllers representing your API. Let's take a look at one.

```php
namespace App\Controllers;

use Contributte\Api\Annotation\Controller\Controller;
use Contributte\Api\Annotation\Controller\Method;
use Contributte\Api\Annotation\Controller\Path;
use Contributte\Api\Annotation\Controller\RootPath;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ApiResponse;
use Contributte\Api\UI\Controller\IController;

/**
 * @Controller
 * @RootPath("/hello")
 */
final class HelloController implements IController
{

    /**
     * @Path("/world")
     * @Method("GET")
     * @param ApiRequest $request
     * @param ApiResponse $response
     * @return ApiResponse
     */
    public function index(ApiRequest $request, ApiResponse $response)
    {
        return $response->writeBody('Hello world!');
    }
}
```

This API by automatic look for all services which implements `Contributte\Api\UI\Controller\IController`. 
Then they are analyzed by annotations loaders and `Contributte\Api\Schema\ApiSchema` is build.

You have to mark your controllers with `@Controller` annotation and also define `@RootPath`.

Each public method with annotations `@Path` and `@Method` will be added to our API scheme and will be triggered in propel request.

One more thing, you have to define your controllers as services.

```yaml
services:
    - App\Controllers\HelloController
```

At the end, open your browser and locate to `localhost/<api-project>/hello/worldd`.

### Request & Response

`Contributte\Api\Http\ApiRequest` & `Contributte\Api\Http\ApiResponse` implement the PSR-7 interfaces. Please  

## Advanced

There are planty of options that might configured.

### Resources

It's boring to register each controller by one, let them be registered over resources. Install another [contributte package](https://github.com/contributte/di).

```
composer install contributte/di
```

And define your resource.

```yaml
extensions:
    api: Contributte\Api\DI\ApiExtension
    api2middlewares: Contributte\Api\DI\Api2MiddlewaresExtension
    middlewares: Contributte\Middlewares\DI\MiddlewaresExtension
    resource: Contributte\DI\Extension\ResourceExtension

resource:
    resources:
        App\Controllers\:
            paths: [%appDir%/controllers]
```

## Playground

I've made a repository with full applications for education.

Take a look: https://github.com/contributte/playground
