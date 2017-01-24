<?php

namespace Contributte\Api\Bridges\Tracy\Panel\ApiPanel;

use Contributte\Api\Schema\ApiSchema;
use Tracy\IBarPanel;

final class ApiPanel implements IBarPanel
{

	/** @var ApiSchema */
	private $schema;

	/**
	 * @param ApiSchema $schema
	 */
	public function __construct(ApiSchema $schema)
	{
		$this->schema = $schema;
	}

	/**
	 * Renders HTML code for custom tab.
	 *
	 * @return string
	 */
	public function getTab()
	{
		ob_start();
		$schema = $this->schema;
		require __DIR__ . '/templates/tab.phtml';

		return ob_get_clean();
	}

	/**
	 * Renders HTML code for custom panel.
	 *
	 * @return string
	 */
	public function getPanel()
	{
		ob_start();
		$schema = $this->schema;
		require __DIR__ . '/templates/panel.phtml';

		return ob_get_clean();
	}

}
