<?php

/**
 * Test: Utils/Helpers
 */

require_once __DIR__ . '/../../bootstrap.php';

use Contributte\Api\Utils\Helpers;
use Tester\Assert;

test(function () {
	Assert::equal('/', Helpers::slashless('/'));
	Assert::equal('/', Helpers::slashless('//'));
	Assert::equal('/', Helpers::slashless('/////'));
	Assert::equal('/foo', Helpers::slashless('/foo'));
	Assert::equal('/foo', Helpers::slashless('//foo'));
	Assert::equal('/foo/', Helpers::slashless('/foo/'));
	Assert::equal('/foo/', Helpers::slashless('//foo//'));
});
