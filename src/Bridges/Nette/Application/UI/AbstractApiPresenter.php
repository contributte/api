<?php

namespace Contributte\Api\Bridges\Nette\Application\UI;

use Contributte\Api\Bridges\Nette\Application\ResponseWrapper;
use Contributte\Api\Dispatcher\IDispatcher;
use Contributte\Api\Http\Request\ApiRequestFactory;
use Contributte\Api\Http\Response\ErrorResponse;
use Contributte\Api\Http\Response\ExceptionResponse;
use Contributte\Api\Http\Response\IApiResponse;
use Exception;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;

abstract class AbstractApiPresenter implements IPresenter
{

	/** @var IDispatcher @inject */
	public $dispatcher;

	/**
	 * @param Request $request
	 * @return IResponse
	 */
	public function run(Request $request)
	{
		// Create our native request from nette request
		$apiRequest = ApiRequestFactory::createFromNetteApplicationRequest($request);

		try {
			// Pass to dispatcher, find handler, process some logic and return response.
			$response = $this->dispatcher->dispatch($apiRequest);

			if (!($response instanceof IApiResponse)) {
				$response = new ErrorResponse('Invalid type of response');
			}
		} catch (Exception $e) {
			$response = new ExceptionResponse($e);
		}

		// Maybe some decorations in the future..
		$wrapped = new ResponseWrapper($response);

		return $wrapped;
	}

}
