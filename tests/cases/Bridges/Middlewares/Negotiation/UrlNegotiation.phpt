<?php

/**
 * Test: Bridges\Middlewares\Negotiation\UrlNegotiator
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Contributte\Api\Bridges\Middlewares\Negotiation\Transformer\JsonTransformer;
use Contributte\Api\Bridges\Middlewares\Negotiation\UrlNegotiator;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Exception\Logical\InvalidTypeException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiDataResponse;
use Contributte\Api\Http\Response\ApiResponse;
use Contributte\Psr7\Psr7Response;
use Contributte\Psr7\Psr7ServerRequest;
use Tester\Assert;

// No transformer
test(function () {
	Assert::exception(function () {
		$negotiation = new UrlNegotiator([]);
		$negotiation->negotiate(new ApiRequest(), new ApiDataResponse());
	}, InvalidStateException::class, 'Please add at least one transformer');
});

// Invalid response type
test(function () {
	Assert::exception(function () {
		$negotiation = new UrlNegotiator([new JsonTransformer()]);
		$negotiation->negotiate(new ApiRequest(), new ApiResponse());
	}, InvalidTypeException::class, 'Response must be Contributte\Api\Http\Response\ApiDataResponse');
});

// NULL negotition (no suitable transformer)
test(function () {
	$negotiation = new UrlNegotiator(['.json' => new JsonTransformer()]);

	$request = new ApiRequest();
	$request = $request->withPsr7(new Psr7ServerRequest('GET', 'https://contributte.org'));
	$response = new ApiDataResponse();
	$response = $response->withPsr7(new Psr7Response());

	// Not transformer found, return NULL;
	Assert::null($negotiation->negotiate($request, $response));
});

// JSON negotiation (according to .json suffix in URL)
test(function () {
	$negotiation = new UrlNegotiator(['.json' => new JsonTransformer()]);

	$request = new ApiRequest();
	$request = $request->withPsr7(new Psr7ServerRequest('GET', 'https://contributte.org/foo.json'));
	$response = new ApiDataResponse();
	$response->write(['foo' => 'bar']);
	$response = $response->withPsr7(new Psr7Response());

	// PSR7 body contains encoded json data
	$res = $negotiation->negotiate($request, $response);
	Assert::equal('{"foo":"bar"}', (string) $res->getPsr7()->getBody());
});

// Fallback negotiation (*)
test(function () {
	$negotiation = new UrlNegotiator(['*' => new JsonTransformer()]);

	$request = new ApiRequest();
	$request = $request->withPsr7(new Psr7ServerRequest('GET', 'https://contributte.org/foo.bar'));
	$response = new ApiDataResponse();
	$response->write(['foo' => 'bar']);
	$response = $response->withPsr7(new Psr7Response());

	// PSR7 body contains encoded json data
	$res = $negotiation->negotiate($request, $response);
	Assert::equal('{"foo":"bar"}', (string) $res->getPsr7()->getBody());
});
