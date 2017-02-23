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

This really powerful annotation-based API library is build on top of the PSR-7 standard. It reuses immutable `Request` & `Response` objects. 
It's well known that [Presenters](https://api.nette.org/2.4/Nette.Application.UI.Presenter.html) are the `alpha` and the `omega` in Nette applications.
The presenters are not very suitable for API requests.

We have taken an idea from Java (Spring Framework) and many others and together we have created a Controller-based API. With full annotation support.

Controllers are small objects which can be registered in DIC as services (same as [Presenters](https://api.nette.org/2.4/Nette.Application.UI.Presenter.html)). 
They have a few public annotated methods. If one of the methods is matched by a router, it is called with `ApiRequest` and `ApiResponse` objects. Here comes the magic, these objects hold `PSR-7` - `Request` & `Response` instances.

That's all. Take a look, it's really simple.

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

As you can see, the architecture is ultra simple. `ApiRequest` & `ApiResponse` have no constructor, so you can easily
extend this class and implement it on your own.

## Documentation

- [Installation - how to register an extension](https://github.com/contributte/api/tree/master/.docs#installation)
- Usage
    - @todo

-----

The development is sponsored by [Tlapnet](http://www.tlapnet.cz). Thank you guys! :+1:

-----

Thank you for testing, reporting and contributing.
