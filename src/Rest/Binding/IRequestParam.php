<?php

namespace Contributte\Api\Rest\Binding;

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
