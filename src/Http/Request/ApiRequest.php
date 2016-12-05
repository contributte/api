<?php

namespace Contributte\Api\Http\Request;

use Nette\Utils\Json;

final class ApiRequest implements IApiRequest
{

	/** @var string */
	private $method;

	/** @var string */
	private $path;

	/** @var array */
	private $query = [];

	/** @var array */
	private $post = [];

	/** @var array */
	private $files = [];

	/** @var mixed */
	private $content;

	/**
	 * @param string $method
	 * @param string $path
	 * @param array $queries
	 * @param array $post
	 * @param array $file
	 * @param mixed $content
	 */
	public function __construct($method, $path, array $queries, array $post, array $file, $content)
	{
		$this->method = $method;
		$this->path = $path;
		$this->query = $queries;
		$this->post = $post;
		$this->files = $file;
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}


	/**
	 * @return mixed
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return mixed
	 */
	public function getJson()
	{
		return Json::decode($this->content, Json::FORCE_ARRAY);
	}

	/**
	 * Get the GET parameter or default value
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getQuery($name, $default = NULL)
	{
		if (array_key_exists($name, $this->query)) {
			return $this->query[$name];
		}

		return $default;
	}

	/**
	 * Get the POST parameter or default value
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getPost($name, $default = NULL)
	{
		if (array_key_exists($name, $this->post)) {
			return $this->post[$name];
		}

		return $default;
	}

	/**
	 * Get the file or default value
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getFiles($name, $default = NULL)
	{
		if (array_key_exists($name, $this->files)) {
			return $this->files[$name];
		}

		return $default;
	}

}
