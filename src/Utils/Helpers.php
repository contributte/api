<?php

namespace Contributte\Api\Utils;

use Nette\Utils\Strings;

final class Helpers
{

	/**
	 * @param string $str
	 * @return string
	 */
	public static function slashless($str)
	{
		return Strings::replace($str, '#/{2,}#', '/');
	}

}
