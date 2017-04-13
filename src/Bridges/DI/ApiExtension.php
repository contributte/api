<?php

namespace Contributte\Api\Bridges\DI;

use Contributte\Api\Bridges\DI\Annotation\DoctrineAnnotationLoader;
use Contributte\Api\Bridges\DI\Annotation\NetteAnnotationLoader;
use Contributte\Api\Bridges\Tracy\BlueScreen\ApiBlueScreen;
use Contributte\Api\Bridges\Tracy\Panel\ApiPanel\ApiPanel;
use Contributte\Api\Dispatcher\ApiDispatcher;
use Contributte\Api\Dispatcher\IDispatcher;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Router\ApiRouter;
use Contributte\Api\Router\IRouter;
use Contributte\Api\Schema\ApiSchema;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Factory\ArrayFactory;
use Contributte\Api\Schema\Generator\ArrayGenerator;
use Contributte\Api\Schema\Validator\Impl\PathValidator;
use Contributte\Api\Schema\Validator\Impl\RootPathValidator;
use Contributte\Api\Schema\Validator\SchemaBuilderValidator;
use Contributte\Api\UI\IHandler;
use Contributte\Api\UI\ServiceHandler;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Validators;

class ApiExtension extends CompilerExtension
{

	// Loader types
	const LOADER = ['annotations', 'neon', 'php'];

	/** @var array */
	private $defaults = [
		'debug' => FALSE,
		'loader' => [
			'type' => 'annotations',
			'impl' => DoctrineAnnotationLoader::class,
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
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$schemaBuilder = NULL;

		// Create loader and fill schema builder
		if ($config['loader']['type'] === 'annotations') {
			$schemaBuilder = $this->loadAnnotations();
		} else if ($config['loader']['type'] === 'neon') {
			throw new InvalidStateException('Not implemented');
		} else if ($config['loader']['type'] === 'php') {
			throw new InvalidStateException('Not implemented');
		} else {
			throw new InvalidStateException('Unknown loader type');
		}

		// Validate schema
		$this->validateSchema($schemaBuilder);

		// Convert schema to array (for DI)
		$generator = new ArrayGenerator();
		$schema = $generator->generate($schemaBuilder);

		$builder->addDefinition($this->prefix('schemaFactory'))
			->setClass(ArrayFactory::class, [$schema]);

		$builder->getDefinition($this->prefix('schema'))
			->setFactory('@' . $this->prefix('schemaFactory') . '::create');
	}

	/**
	 * @param ClassType $class
	 * @return void
	 */
	public function afterCompile(ClassType $class)
	{
		$config = $this->validateConfig($this->defaults);
		$initialize = $class->getMethod('initialize');

		if ($config['debug'] === TRUE) {
			$initialize->addBody('$this->getService(?)->addPanel($this->getByType(?));', ['tracy.bar', ApiPanel::class]);
		}

		$initialize->addBody('?::register($this->getService(?));', [ContainerBuilder::literal(ApiBlueScreen::class), 'tracy.blueScreen']);
	}

	/**
	 * Create annotation loaders and create ApiSchema
	 *
	 * @return SchemaBuilder
	 */
	protected function loadAnnotations()
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Validation loader
		Validators::assertField($config['loader'], 'impl', 'type');

		// Create annotation
		if ($config['loader']['impl'] === DoctrineAnnotationLoader::class) {
			// Doctrine-like @annotations
			$loader = new DoctrineAnnotationLoader($builder);
		} else if ($config['loader']['impl'] === NetteAnnotationLoader::class) {
			// Nette-like-simple @annotations
			$loader = new NetteAnnotationLoader($builder);
		} else {
			throw new InvalidStateException('Unknown annotation loader');
		}

		return $loader->load();
	}

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	protected function validateSchema($builder)
	{
		$validator = new SchemaBuilderValidator();
		$validator->add(new RootPathValidator());
		$validator->add(new PathValidator());

		$validator->validate($builder);
	}

}
