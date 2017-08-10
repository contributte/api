<?php

namespace Contributte\Api\DI;

use Contributte\Api\DI\Loader\DoctrineAnnotationLoader;
use Contributte\Api\Dispatcher\ApiDispatcher;
use Contributte\Api\Dispatcher\IDispatcher;
use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Router\ApiRouter;
use Contributte\Api\Router\IRouter;
use Contributte\Api\Schema\ApiSchema;
use Contributte\Api\Schema\Builder\SchemaBuilder;
use Contributte\Api\Schema\Serialization\ArrayHydrator;
use Contributte\Api\Schema\Serialization\ArraySerializator;
use Contributte\Api\Schema\Validation\PathValidation;
use Contributte\Api\Schema\Validation\RootPathValidation;
use Contributte\Api\Schema\Validator\SchemaBuilderValidator;
use Contributte\Api\Tracy\BlueScreen\ApiBlueScreen;
use Contributte\Api\Tracy\Panel\ApiPanel\ApiPanel;
use Contributte\Api\UI\ContainerServiceHandler;
use Contributte\Api\UI\IHandler;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\Helpers;
use Nette\PhpGenerator\ClassType;

class ApiExtension extends CompilerExtension
{

	// Loader types
	const LOADERS = ['annotations', 'neon', 'php'];

	/** @var array */
	protected $defaults = [
		'debug' => '%debugMode%',
		'loader' => 'annotations',
	];

	/**
	 * Register services
	 *
	 * @return void
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$this->loadConfig();

		$builder->addDefinition($this->prefix('dispatcher'))
			->setClass(IDispatcher::class)
			->setFactory(ApiDispatcher::class);

		$builder->addDefinition($this->prefix('router'))
			->setClass(IRouter::class)
			->setFactory(ApiRouter::class);

		$builder->addDefinition($this->prefix('handler'))
			->setClass(IHandler::class)
			->setFactory(ContainerServiceHandler::class);

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
		$builder = $this->getContainerBuilder();
		$schemaBuilder = $this->getSchemaBuilder();

		// Validate schema
		$this->validateSchema($schemaBuilder);

		// Convert schema to array (for DI)
		$generator = new ArraySerializator();
		$schema = $generator->serialize($schemaBuilder);

		// Register services
		$builder->addDefinition($this->prefix('hydrator'))
			->setClass(ArrayHydrator::class);

		$builder->getDefinition($this->prefix('schema'))
			->setFactory('@' . $this->prefix('hydrator') . '::hydrate', [$schema]);
	}

	/**
	 * @param ClassType $class
	 * @return void
	 */
	public function afterCompile(ClassType $class)
	{
		$config = $this->loadConfig();
		$initialize = $class->getMethod('initialize');

		if ($config['debug'] === TRUE) {
			$initialize->addBody('$this->getService(?)->addPanel($this->getByType(?));', ['tracy.bar', ApiPanel::class]);
		}

		$initialize->addBody('?::register($this->getService(?));', [ContainerBuilder::literal(ApiBlueScreen::class), 'tracy.blueScreen']);
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @return SchemaBuilder
	 */
	protected function getSchemaBuilder()
	{
		$config = $this->loadConfig();

		// Create loader and fill schema builder
		if ($config['loader'] === 'annotations') {
			return $this->loadAnnotations();
		} else if ($config['loader'] === 'neon') {
			throw new InvalidStateException('Not implemented');
		} else if ($config['loader'] === 'php') {
			throw new InvalidStateException('Not implemented');
		} else {
			throw new InvalidStateException('Unknown loader type');
		}
	}

	/**
	 * Create annotation loaders and create SchemaBuilder
	 *
	 * @return SchemaBuilder
	 */
	protected function loadAnnotations()
	{
		$builder = $this->getContainerBuilder();
		$loader = new DoctrineAnnotationLoader($builder);

		return $loader->load();
	}

	/**
	 * @param SchemaBuilder $builder
	 * @return void
	 */
	protected function validateSchema($builder)
	{
		$validator = new SchemaBuilderValidator();
		$validator->add(new RootPathValidation());
		$validator->add(new PathValidation());

		$validator->validate($builder);
	}

	/**
	 * @return array
	 */
	protected function loadConfig()
	{
		$config = $this->validateConfig($this->defaults);
		$this->config = Helpers::expand($config, $this->getContainerBuilder()->parameters);

		return $this->config;
	}

}
