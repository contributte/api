<?php declare(strict_types = 1);

namespace Contributte\Api\Formatter;

use Contributte\Api\Http\ResponseInterface;

interface FormatterInterface
{

	public function format(ResponseInterface $response): ResponseInterface;

}
