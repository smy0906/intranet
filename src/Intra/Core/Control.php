<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 13. 12. 23
 * Time: 오후 6:29
 */

namespace Intra\Core;

use Symfony\Component\HttpFoundation\Request;

class Control
{
	public $remain_query;
	private $controller_root;
	/**
	 * @var Request
	 */
	private $request;

	public function __construct($controller_root, $query, Request $request)
	{
		$this->controller_root = $controller_root;
		$this->remain_query = $query;

		$this->target_file = $this->__getTargetFileByQuery();
		$this->is_exist = file_exists($this->target_file);
		$this->request = $request;
	}

	/**
	 * @return string
	 */
	private function __getTargetFileByQuery()
	{
		return $this->controller_root . '/' . $this->remain_query . ".php";
	}

	public function isExist()
	{
		return $this->is_exist;
	}

	public function call()
	{
		if (!$this->is_exist) {
			throw new \Exception("unknown control query : " . $this->remain_query);
		}
		try {
			return include($this->target_file);
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function getRemainQuery()
	{
		return basename($this->remain_query);
	}

	public function getFullQuery()
	{
		return $this->remain_query;
	}

	public function getRequest()
	{
		return $this->request;
	}
}
