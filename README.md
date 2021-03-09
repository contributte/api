# Contributte/API -> Apitte 

:boom: Powerful API (`PSR-7`, `REST`, `Relay`, `Middleware`, `GraphQL`, `DataQL`, `Annotations`) for [`Nette Framework`](https://github.com/nette/).

-----

:exclamation: This project is discontinued. 

:+1: It has been moved under [Apitte](https://github.com/apitte) organization and split into more repositories (:zap:). 

-----

## Contributte

[![Join the chat](https://img.shields.io/gitter/room/contributte/contributte.svg?style=flat-square)](http://bit.ly/ctteg)

## Apitte

[![Join the chat](https://img.shields.io/gitter/room/apitte/apitte.svg?style=flat-square)](http://bit.ly/apittegitter)

## Migration

Everything is pretty much same except (:warning:) namespaces.

-----

### #1 Install

**Before**

```bash
composer require contributte/api
```

**After**

There are more features and more packages. Just check it out. :muscle:

```bash
composer require apitte/core
composer require apitte/debug
composer require apitte/mapping
composer require apitte/middlewares
composer require apitte/mapping
composer require apitte/openapi
```

-----

### #2 Usage

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

**After**

```php
namespace App\Controllers;

use Apitte\Core\Annotation\Controller\Controller;
use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RootPath;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\UI\Controller\IController;

/**
 * @Controller
 * @RootPath("/hello")
 */
final class HelloController implements IController
{

    /**
     * @Path("/world")
     * @Method("GET")
     */
    public function index(ApiRequest $request, ApiResponse $response)
    {
        return $response->writeBody('Hello world!');
    }
}
```

----

Thank you for understanding. We would like to make API event better.
