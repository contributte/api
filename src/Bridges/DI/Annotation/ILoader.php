<?php

namespace Contributte\Api\Bridges\DI\Annotation;

use Contributte\Api\Schema\Builder\SchemaBuilder;

interface ILoader
{

	/**
	 * @return SchemaBuilder
	 */
	public function load();

}
