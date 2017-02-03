<?php

/**
 * Test: Bridges\Middlewares\Negotiation\Transformer\JsonTransformer
 */

require_once __DIR__ . '/../../../../../bootstrap.php';

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\JsonTransformer;
use Tester\Assert;

// Encode
test(function () {
	$transformer = new JsonTransformer();

	Assert::equal('{"foo":"bar"}', $transformer->encode(['foo' => 'bar']));
});

// Decode
test(function () {
	$transformer = new JsonTransformer();

	Assert::equal(['foo' => 'bar'], $transformer->decode('{"foo":"bar"}'));
});
