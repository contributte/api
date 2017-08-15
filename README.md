# API

:boom: Powerful API (`PSR-7`, `REST`, `Relay`, `Middleware`, `GraphQL`, `DataQL`, `Annotations`) for [`Nette Framework`](https://github.com/nette/).

-----

[![Build Status](https://img.shields.io/travis/contributte/api.svg?style=flat-square)](https://travis-ci.org/contributte/api)
[![Code coverage](https://img.shields.io/coveralls/contributte/api.svg?style=flat-square)](https://coveralls.io/r/contributte/api)
[![Licence](https://img.shields.io/packagist/l/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)

[![Downloads this Month](https://img.shields.io/packagist/dm/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)
[![Downloads total](https://img.shields.io/packagist/dt/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)
[![Latest stable](https://img.shields.io/packagist/v/contributte/api.svg?style=flat-square)](https://packagist.org/packages/contributte/api)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/contributte/contributte.svg?style=flat-square)](http://bit.ly/ctteg)

## Install

```
composer require contributte/api
```

## Version

| State       | Version      | Branch   | PHP      |
|-------------|--------------|----------|----------|
| development | `dev-master` | `master` | `>= 5.6` |
| stable      | `^0.3.0`     | `master` | `>= 5.6` |

## Prolog

This really powerful annotation-based API library is build on top of the PSR-7 standard. It reuses immutable `Request` & `Response` objects. It's well known that [Presenters](https://api.nette.org/2.4/Nette.Application.UI.Presenter.html) are the `alpha` and the `omega` in Nette applications. The presenters are not very suitable for API requests.

We have taken an idea from Java ([Spring Framework](https://spring.io/guides/gs/rest-service/)) and many others and together we have created a Controller-based API. With full annotation support.

Controllers are small objects which can be registered in DIC as services (same as [Presenters](https://api.nette.org/2.4/Nette.Application.UI.Presenter.html)). They have a few public annotated methods. If one of the methods is matched by a router, it is called with `ApiRequest` and `ApiResponse` passed objects.

That's all. Take a look, it's really simple.

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

And register your controller as service.

```yaml
services:
    - App\Controllers\HelloController
```

As you can see, the architecture is ultra simple. `ApiRequest` & `ApiResponse` extend PSR-7, so you can re-use these declared methods.

## Overview

- [Installation - how to register an extension](https://github.com/contributte/api/tree/master/.docs#installation)
- [Middlewares - how to setup middlewares](https://github.com/contributte/api/tree/master/.docs#middlewares)
- [Bridge - how use API & middlewares easily](https://github.com/contributte/api/tree/master/.docs#bridge)
- [Usage - controller showtime](https://github.com/contributte/api/tree/master/.docs#usage)
- [Advanced - complex configuration](https://github.com/contributte/api/tree/master/.docs#advanced)
- [Playground - real examples](https://github.com/contributte/api/tree/master/.docs#playground)

## Maintainers

<table>
  <tbody>
    <tr>
      <td align="center">
        <a href="https://github.com/f3l1x">
            <img width="150" height="150" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=150">
        </a>
        </br>
        <a href="https://github.com/f3l1x">Milan Felix Šulc</a>
      </td>
    </tr>
  <tbody>
</table>

-----

The development is sponsored by [Tlapnet](http://www.tlapnet.cz) and a lot of coffeees. Thank you guys! :+1:

-----

Thank you for testing, reporting and contributing.
