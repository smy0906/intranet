<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 1. 7
 * Time: 오후 7:38
 */

namespace Intra\Core;

class ControlRouteMatch
{
	private $query;
	private $unmatchedQueryTail;
	private $parameters;

	public function __construct($uri, $unmatchedQueryTail, $parameters)
	{
		$this->query = $uri;
		$this->unmatchedQueryTail = $unmatchedQueryTail;
		$this->parameters = $parameters;
	}

	public function query($string)
	{
		$this->query = $string;
	}

	public function __success()
	{
		return true;
	}

	public function __getQuery()
	{
		return $this->query . '/' . $this->unmatchedQueryTail;
	}

	public function __getParameter()
	{
		return $this->parameters;
	}

	/**
	 * @param $string
	 * @return ControlRouteMatch
	 * @throws \Exception
	 */
	public function assertAsInt($string)
	{
		if (!preg_match('/^\d+$/', $this->parameters[$string])) {
			throw new \Exception("parameter $string is not integer");
		}
		return $this;
	}

	public function assertInArray($string, $array)
	{
		if (!in_array($this->parameters[$string], $array)) {
			throw new \Exception("parameter $string is not valid");
		}
		return $this;
	}
}
