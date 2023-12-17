<?php declare(strict_types = 1);

namespace Contributte\Api\Formatter;

use Contributte\Api\Http\DataResponse;
use Contributte\Api\Http\EntityResponse;
use Contributte\Api\Http\ErrorResponse;
use Contributte\Api\Http\PureResponse;
use Contributte\Api\Http\ResponseInterface;
use LogicException;
use Nette\Utils\Json;

class JsonFormatter implements FormatterInterface
{

	public function format(ResponseInterface $response): ResponseInterface
	{
		if ($response instanceof EntityResponse) {
			return $this->formatEntity($response);
		}

		if ($response instanceof DataResponse) {
			return $this->formatData($response);
		}

		if ($response instanceof ErrorResponse) {
			return $this->formatError($response);
		}

		if ($response instanceof PureResponse) {
			return $response;
		}

		throw new LogicException(sprintf('Unsupported result class type "%s"', $response::class));
	}

	private function formatData(DataResponse $result): PureResponse
	{
		$payload = [
			'status' => 'ok',
		];

		$data = $result->getData();
		if ($data !== null) {
			$payload['data'] = $data;
		}

		return PureResponse::create()
			->withPayload(Json::encode($payload))
			->withHeaders(['Content-Type' => 'application/json']);
	}

	private function formatEntity(EntityResponse $result): PureResponse
	{
		$payload = [
			'status' => 'ok',
		];

		$data = $result->getPayload();
		if ($data !== []) {
			$payload['data'] = $data;
		}

		return PureResponse::create()
			->withPayload(Json::encode($payload))
			->withHeaders(['Content-Type' => 'application/json']);
	}

	private function formatError(ErrorResponse $result): PureResponse
	{
		$payload = [
			'status' => 'error',
		];

		$error = [];
		if ($result->getErrorCode() !== null) {
			$error['code'] = $result->getErrorCode();
		}

		if ($result->getMessage() !== null) {
			$error['message'] = $result->getMessage();
		}

		if ($result->getValidations() !== []) {
			$error['validations'] = $result->getValidations();
		}

		if ($error !== []) {
			$payload['error'] = $error;
		}

		return PureResponse::create()
			->withPayload(Json::encode($payload))
			->withHeaders(['Content-Type' => 'application/json']);
	}

}
