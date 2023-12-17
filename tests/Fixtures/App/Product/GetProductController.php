<?php declare(strict_types = 1);

namespace Tests\Fixtures\App\Product;

use Contributte\Api\Controller\JsonController;
use Contributte\Api\Description\Describer;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ErrorResponse;
use Contributte\Api\Http\ResponseInterface;
use Throwable;

final class GetProductController extends JsonController
{

	public static function describe(): Describer
	{
		$d = new Describer();
		$d->withPath('/api/v1/product/<uuid>');
		$d->withMethods([Describer::METHOD_GET]);
		$d->withDescription('Get product');

		return $d;
	}

	public function __invoke(ApiRequest $request): ResponseInterface
	{
		$id = $request->getParameter('uuid');

		// Validate parameters
		if (empty($id)) {
			return ErrorResponse::create()
				->withStatusCode(400)
				->withMessage('Invalid ID');
		}

		try {
			// Load data from DB
			$product = [
				'id' => 1,
				'name' => 'Test',
			];
		} catch (Throwable $e) {
			return ErrorResponse::create()
				->withStatusCode(400)
				->withMessage('Cannot load detail');
		}

		// Send response
		return GetProductResponse::of($product);
	}

}
