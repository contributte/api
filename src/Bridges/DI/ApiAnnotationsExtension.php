<?php

namespace Contributte\Api\Bridges\DI;

use Contributte\Api\Bridges\DI\Annotation\DoctrineAnnotationLoader;
use Contributte\Api\Bridges\DI\Annotation\NetteAnnotationLoader;
use Contributte\Api\Bridges\Tracy\Panel\ApiPanel\ApiPanel;
use Contributte\Api\Dispatcher\ApiDispatcher;
use Contributte\Api\Dispatcher\IDispatcher;
use Contributte\Api\Router\ApiRouter;
use Contributte\Api\Router\IRouter;
use Contributte\Api\Schema\ApiSchema;
use Contributte\Api\Schema\Factory\ArraySchemaFactory;
use Contributte\Api\Schema\Generator\ArrayGenerator;
use Contributte\Api\UI\IHandler;
use Contributte\Api\UI\ServiceHandler;
use Nette\DI\CompilerExtension;
use Nette\InvalidStateException;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Validators;

final class ApiAnnotationsExtension extends CompilerExtension
{

	/** @var array */
	private $defaults = [
		'debugger' => TRUE,
		'annotation' => [
			'loader' => DoctrineAnnotationLoader::class,
		],
	];

	/**
	 * Register services
	 *
	 * @return void
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('dispatcher'))
			->setClass(IDispatcher::class)
			->setFactory(ApiDispatcher::class);

		$builder->addDefinition($this->prefix('router'))
			->setClass(IRouter::class)
			->setFactory(ApiRouter::class);

		$builder->addDefinition($this->prefix('handler'))
			->setClass(IHandler::class)
			->setFactory(ServiceHandler::class);

		$builder->addDefinition($this->prefix('schema'))
			->setClass(ApiSchema::class);

		$builder->addDefinition($this->prefix('panel'))
			->setClass(ApiPanel::class);
	}

	/**
	 * Decorate services
	 *
	 * @return void
	 */
	public function beforeCompile()
	{
		$this->loadAnnotations();
	}

	/**
	 * @param ClassType $class
	 * @return void
	 */
	public function afterCompile(ClassType $class)
	{
		$config = $this->validateConfig($this->defaults);

		if ($config['debugger'] === TRUE) {
			$class->getMethod('initialize')
				->addBody('$this->getService(?)->addPanel($this->getByType(?));', ['tracy.bar', ApiPanel::class]);
		}
	}

	/**
	 * Load annotations
	 *
	 * @return void
	 */
	private function loadAnnotations()
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Validation
		Validators::assertField($config['annotation'], 'loader', 'type');

		// Loaders
		if ($config['annotation']['loader'] === DoctrineAnnotationLoader::class) {

			// Doctrine-like @annotations
			$loader = new DoctrineAnnotationLoader($builder);
			$schema = $loader->load()->generate(new ArrayGenerator());

			$builder->addDefinition($this->prefix('schemaFactory'))
				->setClass(ArraySchemaFactory::class, [$schema]);

			$builder->getDefinition($this->prefix('schema'))
				->setFactory('@' . $this->prefix('schemaFactory') . '::create');

		} else if ($config['annotation']['loader'] === NetteAnnotationLoader::class) {

			// Nette-like-simple @annotations
			$loader = new NetteAnnotationLoader($builder);
			$schema = $loader->load()->generate(new ArrayGenerator());

			$builder->addDefinition($this->prefix('schemaFactory'))
				->setClass(ArraySchemaFactory::class, [$schema]);

			$builder->getDefinition($this->prefix('schema'))
				->setFactory('@' . $this->prefix('schemaFactory') . '::create');

		} else {
			throw new InvalidStateException('Uknown annotation loader');
		}
	}

}
