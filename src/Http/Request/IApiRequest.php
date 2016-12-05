<?php

namespace Contributte\Api\Http\Request;

interface IApiRequest
{

	/**
	 * @return string
	 */
	public function getPath();

	/**
	 * @return string
	 */
	public function getMethod();

	/**
	 * @return mixed
	 */
	public function getJson();

	/**
	 * Get the GET parameter or default value
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getQuery($name, $default = NULL);

	/**
	 * Get the POST parameter or default value
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getPost($name, $default = NULL);

	/**
	 * Get the file or default value
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getFiles($name, $default = NULL);

}
