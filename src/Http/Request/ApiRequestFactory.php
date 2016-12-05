<?php

namespace Contributte\Api\Http\Request;

use Nette\Application\Request;

final class ApiRequestFactory
{

	/**
	 * @param IApiRequest $request
	 * @return ApiRequest
	 */
	public static function createFromNetteApplicationRequest(Request $request)
	{
		$content = file_get_contents('php://input');

		return new ApiRequest(
			strtoupper($request->getMethod()),
			'/' . ltrim($request->getParameter('path'), '/'),
			$request->getParameters(),
			$request->getPost(),
			$request->getFiles(),
			$content ?: NULL
		);
	}

}
