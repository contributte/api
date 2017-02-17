<?php

/**
 * Test: Bridges\DI\Annotation\NetteAnnotationLoader
 */

require_once __DIR__ . '/../../../../../bootstrap.php';

use Contributte\Api\Bridges\DI\Annotation\NetteAnnotationLoader;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\UI\Controller\IController;
use Fixtures\Controllers\FoobarController;
use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use Tester\Assert;

// Check if controller is found and add as dependency to DIC
test(function () {
	$builder = Mockery::mock(ContainerBuilder::class);
	$builder->shouldReceive('findByType')
		->once()
		->with(IController::class)
		->andReturnUsing(function () {
			$controllers = [];
			$controllers[] = $c1 = new ServiceDefinition();
			$c1->setClass(FoobarController::class);

			return $controllers;
		});

	$builder->shouldReceive('addDependency')
		->once()
		->with(FoobarController::class);

	$loader = new NetteAnnotationLoader($builder);
	$schemaBuilder = $loader->load();

	Assert::type(SchemaBuilder::class, $schemaBuilder);

	Mockery::close();
});
