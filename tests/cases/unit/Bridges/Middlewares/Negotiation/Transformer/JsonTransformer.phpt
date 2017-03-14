<?php

/**
 * Test: Bridges\Middlewares\Negotiation\Transformer\JsonTransformer
 */

require_once __DIR__ . '/../../../../../../bootstrap.php';

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\JsonTransformer;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Tester\Assert;
use function GuzzleHttp\Psr7\stream_for;

// Encode
test(function () {
	$transformer = new JsonTransformer();
	$response = new ApiResponse();
	$response = $response->withPsr7(new Response());
	$response->setData(['foo' => 'bar']);

	$response = $transformer->encode($response);
	$response->getPsr7()->getBody()->rewind();

	Assert::equal('{"foo":"bar"}', $response->getBody());
});

// Decode
test(function () {
	$transformer = new JsonTransformer();

	$psr7 = ServerRequest::fromGlobals();
	$psr7 = $psr7->withBody(stream_for('{"foo":"bar"}'));

	$request = new ApiRequest();
	$request = $request->withPsr7($psr7);

	Assert::equal(['foo' => 'bar'], $transformer->decode($request)->getData());
});
