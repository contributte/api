<?php declare(strict_types = 1);

namespace Contributte\Api\Controller;

use Contributte\Api\Description\Describer;
use Contributte\Api\Exception\ParsingException;
use Contributte\Api\Http\ApiRequest;
use Contributte\Api\Http\ErrorResponse;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Nette\Schema\ValidationException;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

abstract class JsonController
{

	abstract public static function describe(): Describer;

	public static function schema(): Schema
	{
		return Expect::mixed();
	}

	/**
	 * @param array<string, mixed> $params
	 */
	protected function validate(Schema $schema, array $params): object
	{
		return (object) (new Processor())->process($schema, $params);
	}

	/**
	 * @template T
	 * @param class-string<T> $entityClass
	 * @return T
	 */
	protected function parseBody(ApiRequest $request, string $entityClass)
	{
		return $this->parseJson((string) $request->getBody(), static::schema(), $entityClass);
	}

	/**
	 * @template T
	 * @param class-string<T> $entityClass
	 * @return T
	 */
	protected function parseQuery(ApiRequest $request, string $entityClass)
	{
		return $this->parseData($request->getUrl()->getQueryParameters(), static::schema(), $entityClass);
	}

	/**
	 * @template T
	 * @param class-string<T> $entityClass
	 * @return T
	 */
	protected function parseJson(string $data, Schema $schema, string $entityClass)
	{
		try {
			/** @var array<string, mixed> $body */
			$body = Json::decode($data, forceArrays: true);

			return $this->parseData($body, $schema, $entityClass);
		} catch (JsonException $e) {
			throw ParsingException::parsingFailed(
				ErrorResponse::create()
					->withException($e)
					->withErrorCode(400)
					->withMessage('Invalid JSON body')
			);
		}
	}

	/**
	 * @template T
	 * @param array<string, mixed> $data
	 * @param class-string<T> $entityClass
	 * @return T
	 */
	protected function parseData(array $data, Schema $schema, string $entityClass)
	{
		try {
			$entity = $this->validate($schema, $data);
			assert($entity instanceof $entityClass);

			return $entity;
		} catch (ValidationException $e) {
			throw ParsingException::parsingFailed(
				ErrorResponse::create()
					->withException($e)
					->withErrorCode(406)
					->withMessage($e->getMessage())
			);
		}
	}

}
