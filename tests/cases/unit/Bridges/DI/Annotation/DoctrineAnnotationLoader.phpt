<?php

/**
 * Test: Bridges\DI\Annotation\DoctrineAnnotationLoader
 */

require_once __DIR__ . '/../../../../../bootstrap.php';

use Contributte\Api\Bridges\DI\Annotation\DoctrineAnnotationLoader;
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

	$loader = new DoctrineAnnotationLoader($builder);
	$schemaBuilder = $loader->load();

	Assert::type(SchemaBuilder::class, $schemaBuilder);

	Mockery::close();
});

// Parse annotations
test(function () {
	$builder = new ContainerBuilder();
	$builder->addDefinition('foobar')
		->setClass(FoobarController::class);

	$loader = new DoctrineAnnotationLoader($builder);
	$schemaBuilder = $loader->load();

	Assert::type(SchemaBuilder::class, $schemaBuilder);
	Assert::count(1, $schemaBuilder->getControllers());

	$controllers = $schemaBuilder->getControllers();
	$controller = array_pop($controllers);

	Assert::equal(FoobarController::class, $controller->getClass());
	Assert::equal('/foobar', $controller->getRootPath());

	Assert::count(2, $controller->getMethods());

	Assert::equal('baz1', $controller->getMethods()['baz1']->getName());
	Assert::equal('/baz1', $controller->getMethods()['baz1']->getPath());
	Assert::equal(['GET'], $controller->getMethods()['baz1']->getMethods());

	Assert::equal('baz2', $controller->getMethods()['baz2']->getName());
	Assert::equal('/baz2', $controller->getMethods()['baz2']->getPath());
	Assert::equal(['GET', 'POST'], $controller->getMethods()['baz2']->getMethods());

	Mockery::close();
});
