<?php

namespace Contributte\Api\Bridges\Nette\DI\Runtime\Annotation;

use Contributte\Api\Annotation\Controller\Controller;
use Contributte\Api\Annotation\Controller\Method;
use Contributte\Api\Annotation\Controller\Path;
use Contributte\Api\Annotation\Controller\RootPath;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Builder\SchemaController;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nette\Reflection\ClassType;

final class DoctrineAnnotationLoader extends AbstractLoader implements ILoader
{

	/** @var AnnotationReader */
	private $reader;

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
		// Read class annotations
		$annotations = $this->createReader()->getClassAnnotations($class);

		// Iterate over all class annotations
		foreach ($annotations as $annotation) {
			// Parse @Controller
			if (get_class($annotation) == Controller::class) {
				// Nothing at this moment.
				continue;
			}

			// Parse @RootPath
			if (get_class($annotation) == RootPath::class) {
				$controller->setRootPath($annotation->getPath());
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

			// Read method annotations
			$annotations = $this->createReader()->getMethodAnnotations($method);

			// Iterate over all method annotations
			foreach ($annotations as $annotation) {
				// Parse @Path =========================
				if (get_class($annotation) == Path::class) {
					$schemaMethod->setPath($annotation->getPath());
					continue;
				}

				// Parse @Method =======================
				if (get_class($annotation) == Method::class) {
					$schemaMethod->setMethod($annotation->getMethod());
					continue;
				}
			}
		}
	}

	/*
	 * HELPERS *****************************************************************
	 */

	/**
	 * @return AnnotationReader
	 */
	private function createReader()
	{
		if (!$this->reader) {
			AnnotationRegistry::registerLoader('class_exists');
			$this->reader = new AnnotationReader();
		}

		return $this->reader;
	}

}
