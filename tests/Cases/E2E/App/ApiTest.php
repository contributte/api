<?php declare(strict_types = 1);

namespace Tests\Cases\E2E\App;

use Contributte\Api\DI\ApiExtension;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Middleware\MiddlewareRunner;
use Contributte\Api\Router\ApiRoute;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Nette\Application\Request as AppRequest;
use Nette\Bridges\HttpDI\HttpExtension;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\Http\Request as HttpRequest;
use Nette\Http\UrlScript;
use Nette\Neon\Neon;
use Nette\Utils\Finder;
use SplFileInfo;
use Tester\Assert;
use Tester\TestCase;
use Throwable;

require_once __DIR__ . '/../../../bootstrap.php';

final class ApiTest extends TestCase
{

	/**
	 * @dataProvider provideCases
	 * @param array<mixed> $def
	 */
	public function testApi(array $def): void
	{
		$httpRequest = new HttpRequest(
			new UrlScript('http://localhost'),
			null,
			null,
			null,
			null,
			$def['request']['method'] ?? 'GET'
		);

		$apiRoute = new ApiRoute(
			$httpRequest->getMethod(),
			'/',
			'Api',
			$def['controller']
		);

		$appRequest = new AppRequest(
			'Api',
			$httpRequest->getMethod(),
			$apiRoute->match($httpRequest),
			$httpRequest->getPost(),
			$httpRequest->getFiles(),
			[]
		);

		$apiRequest = ApiRequest::of($appRequest, $httpRequest);

		/** @var MiddlewareRunner $runner */
		$runner = $this->createContainer()->getByType(MiddlewareRunner::class);

		try {
			$response = $runner->run($apiRequest);
		} catch (Throwable $e) {
			Assert::fail($e->getMessage());
		}

		Assert::equal($def['response']['statusCode'] ?? 200, $response->getStatusCode());
		Assert::equal((trim($def['response']['body'])), $response->getPayload());
	}

	/**
	 * @return iterable<string>
	 */
	public function provideCases(): iterable
	{
		$finder = Finder::findFiles('*.neon')->from(__DIR__ . '/__files__');

		/** @var SplFileInfo $file */
		foreach ($finder as $file) {
			$content = Neon::decodeFile($file->getRealPath());

			yield $file->getRealPath() => [$content];
		}
	}

	public function createContainer(): Container
	{
		return ContainerBuilder::of()
			->withCompiler(function (Compiler $compiler): void {
				$compiler->addExtension('http', new HttpExtension());
				$compiler->addExtension('api', new ApiExtension());
				$compiler->addConfig(Neonkit::load(<<<'NEON'
					services:
						# User defined
						- Tests\Fixtures\App\PingController
						- Tests\Fixtures\App\OpenapiController
						- Tests\Fixtures\App\Product\GetProductController
						- Tests\Fixtures\App\Product\UpdateProductController

					parameters:
						debugMode: false
				NEON
				));
			})->build();
	}

}

(new ApiTest())->run();
