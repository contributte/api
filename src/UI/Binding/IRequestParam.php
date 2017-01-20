<?php

namespace Contributte\Api\UI\Binding;

interface IRequestParam
{

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return mixed
	 */
	public function getValue();

}
