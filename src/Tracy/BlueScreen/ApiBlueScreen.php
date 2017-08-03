<?php

namespace Contributte\Api\Tracy\BlueScreen;

use Contributte\Api\Exception\Runtime\ApiException;
use Tracy\BlueScreen;
use Tracy\Dumper;

final class ApiBlueScreen
{

	/**
	 * @param BlueScreen $blueScreen
	 * @return void
	 */
	public static function register(BlueScreen $blueScreen)
	{
		$blueScreen->addPanel(function ($e) {
			if (!($e instanceof ApiException)) return;
			if (!$e->getContext()) return;

			return [
				'tab' => self::renderTab($e),
				'panel' => self::renderPanel($e),
			];
		});
	}

	/**
	 * @param ApiException $e
	 * @return string
	 */
	private static function renderTab(ApiException $e)
	{
		return 'Api';
	}

	/**
	 * @param ApiException $e
	 * @return string
	 */
	private static function renderPanel(ApiException $e)
	{
		return Dumper::toHtml($e->getContext());
	}

}
