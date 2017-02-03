<?php

/**
 * Test: Utils/Regex
 */

require_once __DIR__ . '/../../bootstrap.php';

use Contributte\Api\Utils\Regex;
use Tester\Assert;

// Regex::match
test(function () {
	Assert::equal(NULL, Regex::match('foo', '#\d+#'));
	Assert::equal(['foo'], Regex::match('foo', '#\w+#'));
	Assert::equal(['foo', 'foo'], Regex::match('foo', '#(\w+)#'));
});
