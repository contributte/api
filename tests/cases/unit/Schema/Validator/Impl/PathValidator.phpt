<?php

/**
 * Test: Schema\Validator\Impl\PathValidator
 */

require_once __DIR__ . '/../../../../../bootstrap.php';

use Contributte\Api\Exception\Logical\ValidationException;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Validator\Impl\PathValidator;
use Tester\Assert;

// Validate: start slash
test(function () {
	$builder = new SchemaBuilder();

	$c1 = $builder->addController('c1');
	$c1m1 = $c1->addMethod('foo');
	$c1m1->setPath('foobar');

	Assert::exception(function () use ($builder) {
		$validator = new PathValidator();
		$validator->validate($builder);
	}, ValidationException::class, '@Path "foobar" in "c1::foo()" must starts with "/" (slash).');
});

// Validate: end slash
test(function () {
	$builder = new SchemaBuilder();

	$c1 = $builder->addController('c1');
	$c1m1 = $c1->addMethod('foo');
	$c1m1->setPath('/foobar/');

	Assert::exception(function () use ($builder) {
		$validator = new PathValidator();
		$validator->validate($builder);
	}, ValidationException::class, '@Path "/foobar/" in "c1::foo()" must not ends with "/" (slash).');
});

// Validate: duplicities
test(function () {
	$builder = new SchemaBuilder();

	$c1 = $builder->addController('c1');
	$c1m1 = $c1->addMethod('foo1');
	$c1m1->setPath('/foobar');
	$c1m1->addMethod('GET');

	$c1m2 = $c1->addMethod('foo2');
	$c1m2->setPath('/foobar');
	$c1m2->addMethod('GET');

	Assert::exception(function () use ($builder) {
		$validator = new PathValidator();
		$validator->validate($builder);
	}, ValidationException::class, 'Duplicate @Path "/foobar" in c1 at methods "foo2()" and "foo1()"');
});

// Validate: duplicities
test(function () {
	$builder = new SchemaBuilder();

	$c1 = $builder->addController('c1');
	$c1m1 = $c1->addMethod('foo1');
	$c1m1->setPath('/foobar');
	$c1m1->setMethods(['GET', 'POST']);

	$c1m2 = $c1->addMethod('foo2');
	$c1m2->setPath('/foobar');
	$c1m2->setMethods(['POST', 'PUT']);

	Assert::exception(function () use ($builder) {
		$validator = new PathValidator();
		$validator->validate($builder);
	}, ValidationException::class, 'Duplicate @Path "/foobar" in c1 at methods "foo2()" and "foo1()"');
});

// Validate: [NOT] duplicities
test(function () {
	$builder = new SchemaBuilder();

	$c1 = $builder->addController('c1');
	$c1m1 = $c1->addMethod('foo1');
	$c1m1->setPath('/foobar');
	$c1m1->addMethod('GET');

	$c1m2 = $c1->addMethod('foo2');
	$c1m2->setPath('/foobar');
	$c1m2->setMethods(['POST']);

	try {
		$validator = new PathValidator();
		$validator->validate($builder);
	} catch (Exception $e) {
		Assert::fail('This is fail. Paths+Method are different.');
	}
});
