<?php declare(strict_types = 1);

namespace Tests\Fixtures\App\Product;

use Contributte\Api\Http\EntityResponse;

class GetProductResponse extends EntityResponse
{

	/**
	 * @param array<string, scalar> $detail
	 */
	public static function of(array $detail): self
	{
		$self = self::create();
		$self->payload = $detail;

		return $self;
	}

}
