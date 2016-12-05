<?php

namespace Contributte\Api\Bridges\Nette\DI\Runtime\Annotation;

use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Builder\SchemaController;
use Nette\Reflection\ClassType;
use Nette\Utils\Strings;

final class NetteAnnotationLoader extends AbstractLoader implements ILoader
{

	/**
	 * @return SchemaBuilder
	 */
	public function load()
	{
		$schemeBuilder = new SchemaBuilder();

		// Find all controllers by type (interface, annotation)
		$controllers = $this->findControllers();

		// Iterate over all controllers
		foreach ($controllers as $def) {
			// Create reflection
			$class = ClassType::from($def->getClass());

			// Create scheme endpoint
			$schemeController = $schemeBuilder->addController($def->getClass());

			// Parse @Controller, @RootPath
			$this->parseControllerClassAnnotations($schemeController, $class);

			// Parse @Method, @Path
			$this->parseControllerMethodsAnnotations($schemeController, $class);
		}

		return $schemeBuilder;
	}

	/**
	 * @param SchemaController $controller
	 * @param ClassType $class
	 * @return void
	 */
	protected function parseControllerClassAnnotations(SchemaController $controller, ClassType $class)
	{
		// Iterate over all class annotations
		foreach ($class->getAnnotations() as $name => $annotation) {
			// Parse @Controller
			if ($this->compare($name, 'Controller')) {
				// Nothing at this moment.
				continue;
			}

			// Parse @RootPath
			if ($this->compare($name, 'RootPath')) {
				$controller->setRootPath($annotation[0]);
				continue;
			}
		}
	}

	/**
	 * @param SchemaController $controller
	 * @param ClassType $class
	 * @return void
	 */
	protected function parseControllerMethodsAnnotations(SchemaController $controller, ClassType $class)
	{
		// Iterate over all methods in class
		foreach ($class->getMethods() as $method) {
			// Append method to scheme
			$schemaMethod = $controller->addMethod($method->getName());

			// Iterate over all method annotations
			foreach ($method->getAnnotations() as $name => $annotation) {
				// Internal nette param
				if ($name === 'name') continue;

				// Parse @Path =========================
				if ($this->compare($name, 'Path')) {
					$schemaMethod->setPath($annotation[0]);
					continue;
				}

				// Parse @Method =======================
				if ($this->compare($name, 'Method')) {
					$schemaMethod->setMethod($annotation[0]);
					continue;
				}
			}
		}
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param string $annotation
	 * @param string $expected
	 * @return bool
	 */
	private function compare($annotation, $expected)
	{
		return Strings::match(strtolower($annotation), sprintf('#%s$$#U', strtolower($expected))) !== NULL;
	}

}
