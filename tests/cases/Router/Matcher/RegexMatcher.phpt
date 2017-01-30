<?php

/**
 * Test: Router/Matcher/RegexMatcher
 */

require_once __DIR__ . '/../../../bootstrap.php';

use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Router\Matcher\RegexMatcher;
use Contributte\Api\Schema\Endpoint;
use Contributte\Api\Schema\EndpointParam;
use Contributte\Psr7\Psr7ServerRequest;
use Tester\Assert;

test(function () {
	$endpoint = new Endpoint();
	$endpoint->setPattern('#^/users/(?P<id>[^/]+)#');

	$id = new EndpointParam();
	$id->setName('id');
	$endpoint->addParam($id);

	$psr7 = new Psr7ServerRequest('GET', 'http://example.com/users/22/');
	$request = (new ApiRequest())->withPsr7($psr7);

	$matcher = new RegexMatcher();
	$matched = $matcher->match($endpoint, $request);

	Assert::type(ApiRequest::class, $matched);
	Assert::same(TRUE, $matched->hasParameter('id'));
	Assert::same('22', $matched->getParameter('id')->getValue());
});
