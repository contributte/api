<?php declare(strict_types = 1);

namespace Tests\Fixtures\App;

use Contributte\Api\Router\ApiRoute;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;
use Tests\Fixtures\App\Product\GetProductController;
use Tests\Fixtures\App\Product\UpdateProductController;

class RouterFactory
{

	public function create(): Router
	{
		$router = new RouteList();

//		$api = $router->withPath('/api/v1');
//		$api->add(ApiRoute::of('/ping')->method('GET')->use(PingController::class));

		$router[] = new ApiRoute('GET', '/api/v1/ping', 'ApiV1', PingController::class);

		$router[] = new ApiRoute('GET', '/api/v1/product/<id>', 'ApiV1', GetProductController::class);
		$router[] = new ApiRoute('POST', '/api/v1/product<id>', 'ApiV1', UpdateProductController::class);

		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

		return $router;
	}

}
