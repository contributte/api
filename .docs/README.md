# API - Guidelines

## Content

- [Installation - how to register an extension](#installation)
- [Middlewares - how to setup middlewares](#middlewares)
- [Advanced - complex configuration](#advanced)

## Installation

Simpliest way to register this API is via [Nette\DI\CompilerExtension](https://api.nette.org/2.4/Nette.DI.CompilerExtension.html).

```
composer require contributte/api
```

```yaml
extensions:
    api: Contributte\Api\Bridges\DI\ApiAnnotationsExtension
```

## Middlewares

This API is mainly based on middlewares. You should register also middleware extension.

```
composer require contributte/middlewares
```

```yaml
extensions:
    middlewares: Contributte\Middlewares\DI\NetteMiddlewareExtension
```

## Usage

### Controllers

Your job is to create a couple of controllers representing your API. Let's take a look at one.

```php
use Contributte\Api\Annotation\Controller\Controller;
use Contributte\Api\Annotation\Controller\Method;
use Contributte\Api\Annotation\Controller\Path;
use Contributte\Api\Annotation\Controller\RootPath;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
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
        $response->setBody('Hello world!');

        return $response;
    }
}
```

This API by automatic search for all services which implement `Contributte\Api\UI\Controller\IController`. 
Then they are analyze by annotations loaders and API scheme is built.

You have to mark your controllers with `@Controller` annotation and also define `@RootPath`.

Each public method with annotations `@Path` and `@Method` will be added to our API scheme and will be triggered in propel request.

## Advanced

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
        class: Contributte\Api\Bridges\Middlewares\ApiMiddleware([
            Contributte\Api\Bridges\Middlewares\ApiPrefix('api')
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

middlewares:
    middlewares:
        - @middleware.tracy
        - @middleware.basepath
        - @middleware.router
```
