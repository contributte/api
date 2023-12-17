<?php declare(strict_types = 1);

namespace Contributte\Api\Formatter;

use Contributte\Api\Http\ResponseInterface;

class MultiFormatter implements FormatterInterface
{

	/** @var FormatterInterface[] */
	protected array $formatters = [];

	/**
	 * @param FormatterInterface[] $formatters
	 */
	public function __construct(array $formatters)
	{
		$this->formatters = $formatters;
	}

	public function add(FormatterInterface $formatter): void
	{
		$this->formatters[] = $formatter;
	}

	public function format(ResponseInterface $response): ResponseInterface
	{
		foreach ($this->formatters as $formatter) {
			$response = $formatter->format($response);
		}

		return $response;
	}

}
