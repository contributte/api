<?php

namespace Contributte\Api\Bridges\Nette\DI\Runtime\Annotation;

use Contributte\Api\Schema\Builder\SchemaBuilder;

interface ILoader
{

	/**
	 * @return SchemaBuilder
	 */
	public function load();

}
