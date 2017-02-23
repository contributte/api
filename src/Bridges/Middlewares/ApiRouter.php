<?php

namespace Contributte\Api\Bridges\Middlewares;

use Contributte\Api\Exception\Logical\InvalidStateException;
use Contributte\Api\Http\Request\ApiRequest;
use Contributte\Api\Http\Response\ApiResponse;
use Contributte\Api\Utils\Regex;

class ApiRouter
{

	// Attributes in ServerRequestInterface
	const ATTR_MATCHED_PATTERN = 'C-Api-Router-Pattern';
	const ATTR_MATCHED_REGEX = 'C-Api-Router-Regex';
	const ATTR_MATCHED_PATH = 'C-Api-Router-Path';
	const ATTR_MATCHED_ATTRS = 'C-Api-Router-Attrs';
	const ATTR_MATCHED_ATTR = 'C-Api-Router-Attr';

	// Special variables in URL
	const ATTR_VARIABLE_URL = 'api';
	const ATTR_VARIABLE_FORMAT = 'format';

	/** @var string */
	protected $mask;

	/**
	 * @param string $mask
	 */
	public function __construct($mask)
	{
		$this->mask = $mask;
	}

	/**
	 * API - INVOKING **********************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @param callable $next
	 * @return ApiResponse
	 */
	public function invoke(ApiRequest $request, ApiResponse $response, callable $next)
	{
		$matched = $this->match($request, $response);

		// Handle matched point (by request)
		if ($matched !== NULL) {
			// Pass to next invoker
			return $next($matched, $response);
		}

		// Pass to fallback
		return $this->fallback($request, $response);
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiRequest|NULL
	 */
	protected function match(ApiRequest $request, ApiResponse $response)
	{
		$route = $this->compile();
		$psr7 = $request->getPsr7();
		$path = $psr7->getUri()->getPath();

		// Try match URL path
		$match = Regex::match($path, $route['regex']);

		// We have a match, hallelujah!
		if ($match !== NULL) {
			$psr7 = $psr7
				->withAttribute(self::ATTR_MATCHED_PATTERN, $route['pattern'])
				->withAttribute(self::ATTR_MATCHED_REGEX, $route['regex'])
				->withAttribute(self::ATTR_MATCHED_PATH, $path)
				->withAttribute(self::ATTR_MATCHED_ATTRS, $match);

			foreach ($route['variables'] as $variable) {
				if (isset($match[$variable])) {
					$psr7 = $psr7->withAttribute(sprintf('%s-%s', self::ATTR_MATCHED_ATTR, $variable), $match[$variable]);
				}
			}

			// API: url attribute
			if (isset($match[self::ATTR_VARIABLE_URL])) {
				$psr7 = $psr7->withAttribute(ApiMiddleware::ATTR_URL, $match[self::ATTR_VARIABLE_URL]);
			}

			// API: format attribute
			if (isset($match[self::ATTR_VARIABLE_FORMAT])) {
				$psr7 = $psr7->withAttribute(ApiMiddleware::ATTR_FORMAT, $match[self::ATTR_VARIABLE_FORMAT]);
			}

			// Replace psr7 request in this api request
			$request = $request->withPsr7($psr7);

			return $request;
		}

		return NULL;
	}

	/**
	 * @return array
	 */
	protected function compile()
	{
		$regex = sprintf('#%s#U', $this->mask);

		// Build route
		$route = [
			'pattern' => $this->mask,
			'regex' => $regex,
			'variables' => [],
		];

		// Match and compile regex variables
		$matched = Regex::matchAll($this->mask, '#{(\?)?([a-zA-Z0-9]+)(?:\:(.*))?}#U');
		if ($matched) {
			$variables = [];
			foreach ($matched[0] as $n => $variable) {
				$optional = !empty($matched[1][$n]);
				$name = $matched[2][$n];
				$re = $matched[3][$n] ?: '.+';

				// Validate multi usage of 1 variable
				if (in_array($name, $variables)) throw new InvalidStateException(sprintf('Variable %s is already used', $name));
				$variables[] = $name;

				// Update regex
				$regex = str_replace($variable, sprintf('(?P<%s>%s)%s', $name, $re, $optional ? '?' : ''), $regex);
			}

			$route['variables'] = $variables;
			$route['regex'] = $regex;
		}

		return $route;
	}

	/**
	 * @param ApiRequest $request
	 * @param ApiResponse $response
	 * @return ApiResponse
	 */
	protected function fallback(ApiRequest $request, ApiResponse $response)
	{
		$psr7 = $response
			->getPsr7()
			->withStatus(404);

		$psr7->getBody()
			->write('No matched middleware route by given URL');

		return $response->withPsr7($psr7);
	}

}
