<?php

/**
 * Test: Router\ApiRouter
 */

require_once __DIR__ . '/../../../bootstrap.php';

use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Router\ApiRouter;
use Contributte\Api\Schema\ApiSchema;
use Contributte\Api\Schema\Endpoint;
use Contributte\Api\Schema\EndpointParameter;
use Tester\Assert;

// Match parameter {id}
test(function () {
	$endpoint = new Endpoint();
	$endpoint->addMethod('GET');
	$endpoint->setPattern('#^/users/(?P<id>[^/]+)#');

	$id = new EndpointParameter();
	$id->setName('id');
	$endpoint->addParameter($id);

	$schema = new ApiSchema();
	$schema->addEndpoint($endpoint);

	$request = ApiRequest::fromGlobals()->withNewUri('http://example.com/users/22/');
	$router = new ApiRouter($schema);
	$matched = $router->match($request);

	Assert::type(ApiRequest::class, $matched);
	Assert::true($matched->hasParameter('id'));
	Assert::equal('22', $matched->getParameter('id'));
});

// Match parameters {foo}/{bar}
test(function () {
	$endpoint = new Endpoint();
	$endpoint->addMethod('GET');
	$endpoint->setPattern('#^/users/(?P<foo>[^/]+)/(?P<bar>[^/]+)#');

	$foo = new EndpointParameter();
	$foo->setName('foo');
	$endpoint->addParameter($foo);

	$bar = new EndpointParameter();
	$bar->setName('bar');
	$endpoint->addParameter($bar);

	$schema = new ApiSchema();
	$schema->addEndpoint($endpoint);

	$request = ApiRequest::fromGlobals()->withNewUri('http://example.com/users/1/baz');
	$router = new ApiRouter($schema);
	$matched = $router->match($request);

	Assert::type(ApiRequest::class, $matched);
	Assert::true($matched->hasParameter('foo'));
	Assert::equal('1', $matched->getParameter('foo'));
	Assert::true($matched->hasParameter('bar'));
	Assert::equal('baz', $matched->getParameter('bar'));
});
