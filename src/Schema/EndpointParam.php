<?php

namespace Contributte\Api\Schema;

final class EndpointParam
{

	const TYPE_SCALAR = 1;
	const TYPE_STRING = 2;
	const TYPE_INTEGER = 3;
	const TYPE_FLOAT = 4;
	const TYPE_BOOLEAN = 5;
	const TYPE_DATETIME = 6;

	/** @var string */
	private $name;

	/** @var int */
	private $type;

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param int $type
	 * @return void
	 */
	public function setType($type)
	{
		// @todo validation
		$this->type = $type;
	}

}
