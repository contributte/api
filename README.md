# API

:boom: Powerful API (`PSR-7`, `REST`, `Relay`, `Middleware`, `GraphQL`, `DataQL`, `Annotations`) for [`Nette Framework`](https://github.com/nette/).

-----

[![Build Status](https://img.shields.io/travis/contributte/api.svg?style=flat-square)](https://travis-ci.org/contributte/api)
[![Code coverage](https://img.shields.io/coveralls/contributte/api.svg?style=flat-square)](https://coveralls.io/r/contributte/api)
[![HHVM Status](https://img.shields.io/hhvm/contributte/api.svg?style=flat-square)](http://hhvm.h4cc.de/package/contributte/api)
[![Licence](https://img.shields.io/packagist/l/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)

[![Downloads this Month](https://img.shields.io/packagist/dm/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)
[![Downloads total](https://img.shields.io/packagist/dt/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)
[![Latest stable](https://img.shields.io/packagist/v/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)
[![Latest unstable](https://img.shields.io/packagist/vpre/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/contributte/contributte.svg?style=flat-square)](http://bit.ly/ctteg)

## Install

```
composer require contributte/api
```

## Version

| State       | Version | Branch   | PHP      |
|-------------|---------|----------|----------|
| development | `^0.2`  | `master` | `>= 5.6` |

## Prolog

This really powerful annotation based API library is build on top of the PSR-7 standard. It reuses immutable `Request` & `Response` objects. 
It's well known that in Nette applications are an `alfa` and an `omega` `Nette\Appliction\UI\Presenter`(s). We've took an idea from 
Java (Spring Framework) and many others and together we've created Controller-based API.

Controllers are small parts which can be register to DIC. They have a few public annotated methods. If the method is matched by router, it obtained
`ApiRequest` and `ApiResponse`. Here's come the magic, these simple objects hold `PSR-7` - `Request` & `Response` instances.

Thats all. Take a look, it's really simple.

```php
use Contributte\Api\Annotation\Controller\Controller;
use Contributte\Api\Annotation\Controller\Method;
use Contributte\Api\Annotation\Controller\Path;
use Contributte\Api\Annotation\Controller\RootPath;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;

/**
 * @Controller
 * @RootPath("/users")
 */
final class UsersController
{

    /**
     * @Path("/")
     * @Method("GET")
     * @param ApiRequest $request
     * @param ApiResponse $response
     * @return ApiResponse
     */
    public function index(ApiRequest $request, ApiResponse $response)
    {
        $response->getPsr7()->getBody()->write('Hello world!');

        return $response;
    }
}
```

## Documentation

- [Installation - how to register an extension](https://github.com/contributte/api/tree/master/.docs#installation)

-----

The development is sponsored by [Tlapnet](http://www.tlapnet.cz). Thank you guys! :+1:

-----

Thank you for testing, reporting and contributing.
