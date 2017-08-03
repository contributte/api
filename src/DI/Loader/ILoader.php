<?php

namespace Contributte\Api\DI\Loader;

use Contributte\Api\Schema\Builder\SchemaBuilder;

interface ILoader
{

	/**
	 * @return SchemaBuilder
	 */
	public function load();

}
