<?php

namespace Contributte\Api\Annotation\Controller;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Method
{

	/** @var string */
	private $method;

	/**
	 * @param array $values
	 */
	public function __construct(array $values)
	{
		if (isset($values['value']) && is_string($values['value'])) {
			$this->method = $values['value'];
		} else if (isset($values['method'])) {
			$this->method = $values['method'];
		} else {
			throw new AnnotationException('No method given');
		}
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

}
