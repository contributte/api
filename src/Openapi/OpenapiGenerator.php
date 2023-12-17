<?php declare(strict_types = 1);

namespace Contributte\Api\Openapi;

use Nette\DI\Container;

class OpenapiGenerator
{

	protected Container $container;

	/** @var array<string, mixed> */
	private array $options = [];

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @param array<string, mixed> $options
	 */
	public function setOptions(array $options): void
	{
		$this->options = $options;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function document(): array
	{
		$doc = [
			'openapi' => $this->options['openapi_version'] ?? '3.0.3',
			'info' => [
				'title' => $this->options['app_title'] ?? 'API',
			],
			'paths' => [],
		];

		if (isset($this->options['version'])) {
			$doc['info']['version'] = $this->options['version'];
		}

		// @todo fetch controllers
		//$endpoints = $this->container->findByType('faketype');
		//foreach ($endpoints as $endpoint) {
		//	$descriptor = $endpoint->describe();
		//
		//	// Create new or take previous path
		//	$path = $doc['paths'][$descriptor->getPath()] ?? [];
		//
		//	// Build doc for HTTP methods
		//	foreach ($descriptor->getMethods() as $method) {
		//		$path[strtolower($method)] = [
		//			'summary' => $descriptor->getDescription(),
		//		];
		//	}
		//
		//	// Ensure there is a proper structure
		//	if (!isset($doc['paths'][$descriptor->getPath()])) {
		//		$doc['paths'][$descriptor->getPath()] = [];
		//	}
		//
		//	$doc['paths'][$descriptor->getPath()] = $path;
		//}

		return $doc;
	}

}
