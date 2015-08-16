<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-08-16
 * Time: 오후 4:18
 */

namespace Intra\Core;

class TwigResponse
{
	private $response_array;

	public function __construct()
	{
		$this->response_array = array();
	}

	public function add(array $array)
	{
		$this->response_array = array_merge($this->response_array, $array);
	}

	public function get()
	{
		return $this->response_array;
	}
}
