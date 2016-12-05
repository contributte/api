<?php

namespace Contributte\Api\Annotation\Controller;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Path
{

	/** @var string */
	private $path;

	/**
	 * @param array $values
	 */
	public function __construct(array $values)
	{
		if (isset($values['value']) && is_string($values['value'])) {
			$this->path = $values['value'];
		} else if (isset($values['path'])) {
			$this->path = $values['path'];
		} else {
			throw new AnnotationException('No path given');
		}
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

}
