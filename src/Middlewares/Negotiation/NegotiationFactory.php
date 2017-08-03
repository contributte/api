<?php

namespace Contributte\Api\Middlewares\Negotiation;

class NegotiationFactory
{

	/**
	 * @param array $transformers
	 * @return SuffixNegotiator
	 */
	public static function bySuffix(array $transformers)
	{
		return new SuffixNegotiator($transformers);
	}

}
