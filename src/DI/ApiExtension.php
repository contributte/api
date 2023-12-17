<?php declare(strict_types = 1);

namespace Contributte\Api\DI;

use Contributte\Api\Controller\OpenapiController;
use Contributte\Api\Controller\PingController;
use Contributte\Api\Formatter\JsonFormatter;
use Contributte\Api\Formatter\MultiFormatter;
use Contributte\Api\Middleware\DispatcherMiddleware;
use Contributte\Api\Middleware\MiddlewareRunner;
use Contributte\Api\Middleware\NegotiationMiddleware;
use Contributte\Api\Middleware\TracyMiddleware;
use Contributte\Api\Openapi\OpenapiGenerator;
use Contributte\Api\Presenter\MiddlewarePresenter;
use Nette\Application\IPresenterFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Nette\DI\Helpers;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
class ApiExtension extends CompilerExtension
{

	private const DEFAULT_MIDDLEWARES = [
		'tracy' => [TracyMiddleware::class, ['%debugMode%']],
		'negotiation' => [NegotiationMiddleware::class, []],
		'dispatcher' => [DispatcherMiddleware::class, []],
	];

	public function getConfigSchema(): Schema
	{
		$expectService = Expect::anyOf(
			Expect::string()->required()->assert(fn ($input) => str_starts_with($input, '@') || class_exists($input) || interface_exists($input)),
			Expect::type(Statement::class)->required(),
		);

		return Expect::structure([
			'middlewares' => Expect::arrayOf(clone $expectService),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$runnerDef = $builder->addDefinition($this->prefix('middleware.runner'))
			->setFactory(MiddlewareRunner::class);

		// Apply default middlewares
		if ($config->middlewares === []) {
			foreach (self::DEFAULT_MIDDLEWARES as $formatter) {
				$runnerDef->addSetup('add', [
					new Statement(
						$formatter[0],
						Helpers::expand($formatter[1], $builder->parameters) // @phpstan-ignore-line
					),
				]);
			}
		}

		// Apply defined middlewares
		foreach ((array) $config->middlewares as $middleware) {
			if (is_string($middleware)) {
				$runnerDef->addSetup('add', [new Statement($middleware)]);
			} elseif (is_array($middleware)) {
				$runnerDef->addSetup('add', [new Statement($middleware)]);
			} elseif ($middleware instanceof Statement) {
				$runnerDef->addSetup('add', [$middleware]);
			}
		}

		$builder->addDefinition($this->prefix('presenter.middleware'))
			->setFactory(MiddlewarePresenter::class);

		$builder->addDefinition($this->prefix('openapi.generator'))
			->setFactory(OpenapiGenerator::class);

		$builder->addDefinition($this->prefix('controller.ping'))
			->setFactory(PingController::class);

		$builder->addDefinition($this->prefix('controller.openapi'))
			->setFactory(OpenapiController::class);

		$builder->addDefinition($this->prefix('formatter'))
			->setFactory(MultiFormatter::class, [[new Statement(JsonFormatter::class)]]);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$presenterFactory = $builder->getByType(IPresenterFactory::class);
		if ($presenterFactory !== null) {
			$presenterFactoryDef = $builder->getDefinition($presenterFactory);
			assert($presenterFactoryDef instanceof ServiceDefinition);
			$presenterFactoryDef->addSetup('setMapping', [
				[
					'ContributteApi' => 'Contributte\Api\Presenter\*Presenter',
				],
			]);
		}
	}

}
