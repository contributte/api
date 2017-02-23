<?php

namespace Contributte\Api\Bridges\DI\Annotation;

use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Builder\SchemaController;
use Contributte\Api\Utils\Regex;
use Nette\Reflection\ClassType;

final class NetteAnnotationLoader extends AnnotationLoader
{

	/**
	 * @return SchemaBuilder
	 */
	public function load()
	{
		$schemeBuilder = new SchemaBuilder();

		// Find all controllers by type (interface, annotation)
		$controllers = $this->findControllers();

		// Add controllers as dependencies to DIC
		$this->addDependencies($controllers);

		// Iterate over all controllers
		foreach ($controllers as $def) {
			// Create reflection
			$class = ClassType::from($def->getClass());

			// Check if a controller has @Controller annotation, otherwise, skip this controller
			if (!$class->hasAnnotation('Controller')) continue;

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
			// Parse @RootPath
			if ($this->compare($name, 'RootPath')) {
				$controller->setRootPath((string) $annotation[0]);
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
					$schemaMethod->setPath((string) $annotation[0]);
					continue;
				}

				// Parse @Method =======================
				if ($this->compare($name, 'Method')) {
					$schemaMethod->appendMethods((array) $annotation[0]);
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
		return Regex::match(strtolower($annotation), sprintf('#%s$$#U', strtolower($expected))) !== NULL;
	}

}
