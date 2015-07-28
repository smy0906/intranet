<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 1. 7
 * Time: 오후 7:38
 */

namespace Intra\Core;

use Symfony\Component\HttpFoundation\Request;

/**
 * @property mixed routeFile
 * @property Request request
 * @property string $query
 * @property ControlRoute_match lastMatch
 */
class Route
{
	private $path;

	function __construct($path)
	{
		$this->path = $path;
	}

	public function isExist()
	{
		$this->routeFile = $this->path . '/__route.php';
		return file_exists($this->routeFile);
	}

	public function route($query, $request)
	{
		$this->request = $request;
		$this->query = $query;

		include($this->routeFile);
		$this->postProcessLastMatchIfExist();

		return $this->query;
	}

	public function postProcessLastMatchIfExist()
	{
		if ($this->lastMatch !== null) {
			if ($this->lastMatch->__success()) {
				$this->query = $this->lastMatch->__getQuery();
				$parameters = $this->lastMatch->__getParameter();
				foreach ($parameters as $key => $value) {
					$this->request->attributes->set($key, $value);
				}
			}
		}
	}

	/**
	 * @param $pattern
	 * @return ControlRoute_match
	 */
	public function matchIf($pattern)
	{
		$this->postProcessLastMatchIfExist();

		$this->lastMatch = null;

		$regex = '/\{(\w+)\}/';

		//find paramaters
		{
			preg_match_all($regex, $pattern, $match);
			$parameterNames = $match[1];
		}

		//build regex for parameter
		{
			$pattern_regex = $pattern;
			$pattern_regex = preg_replace('/^\/+|\/+$/', '', $pattern_regex);
			$pattern_regex = str_replace('/', '\\' . '/+', $pattern_regex);
			$pattern_regex = preg_replace($regex, '([^\/\\?&]+)', $pattern_regex);
			$pattern_regex = '/^' . $pattern_regex . '(.*)$/';
		}

		if (preg_match($pattern_regex, $this->query, $match)) {
			$parameters = array();
			foreach ($parameterNames as $index => $parameterName) {
				$parameters[$parameterName] = $match[$index + 1];
			}

			$unmatchedQueryTail = end($match);

			$this->lastMatch = new ControlRoute_match($this->query, $unmatchedQueryTail, $parameters);
			return $this->lastMatch;
		}

		return new ControlRoute_null();
	}
}

class ControlRoute_match
{
	private $query;
	private $unmatchedQueryTail;
	private $parameters;

	function __construct($uri, $unmatchedQueryTail, $parameters)
	{
		$this->query = $uri;
		$this->unmatchedQueryTail = $unmatchedQueryTail;
		$this->parameters = $parameters;
	}

	function query($string)
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
	 * @return ControlRoute_match
	 * @throws \Exception
	 */
	function assertAsInt($string)
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

class ControlRoute_null
{
	function __call($a, $b)
	{
		return $this;
	}
}