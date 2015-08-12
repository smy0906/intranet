<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 13. 12. 23
 * Time: 오후 6:29
 */

namespace Intra\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QueryProcessor
{
	const CONSTRUCT_MAGIC_FUNCTION = '__construct';

	public $remain_query;
	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	private $request;
	private $controller_root;
	private $executed_query;

	public function __construct($controller_root, $remain_query, Request $request)
	{
		$this->controller_root = $controller_root;
		$this->remain_query = $remain_query;
		$this->request = $request;
		$this->executed_query = null;
	}

	public static function run($control_dir, $view_dir, $request = null)
	{
		$query = $_SERVER['REQUEST_URI'];
		#$query = preg_replace("/[^\w\/_\-\.]/", "", $query);

		if ($request === null) {
			$request = Request::createFromGlobals();
		}

		$control = new QueryProcessor($control_dir, $query, $request);
		$return_by_controler = $control->__act();
		if ($return_by_controler === false) {
			return false;
			#throw new Exception('control action error');
		}
		if (!is_array($return_by_controler)) {
			exit((string)$return_by_controler);
		}
		$view = new View($view_dir, $control->getRoutedQuery());
		if (!$view->isExist()) {
			throw new \Exception('no view');
		}
		$view->act($return_by_controler);

		return true;
	}

	/**
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function __act()
	{
		//__constructor.php
		$this->processConstructor();
		//__route.php
		$this->processRoute();
		//parse query
		list($matched_query, $unmatched_query_tail) = $this->parseQuery();

		//control
		if ($this->isSubfolder($matched_query)) {
			$subQueryProcessor = new QueryProcessor(
				$this->controller_root . '/' . $matched_query,
				$unmatched_query_tail,
				$this->request
			);
			$ret = $subQueryProcessor->__act();
			$this->executed_query = $matched_query . '/' . $subQueryProcessor->getRoutedQuery();
			return $ret;
		} elseif ($this->isAvailableController($matched_query)) {
			try {
				$target = new Control(
					$this->controller_root,
					$matched_query,
					$this->request
				);
				$this->executed_query = $matched_query;
				$ret = $target->call();
			} catch (AjaxMessage $e) {
				$ret = $e->getMessage();
				echo($ret);
			}
			if ($ret instanceof Response) {
				$ret->send();
				exit;
			}
			return $ret;
		}
		return false;
	}

	private function isAvailableController($controller)
	{
		return is_file($this->controller_root . '/' . $controller . ".php");
	}

	private function isSubfolder($controller)
	{
		return is_dir($this->controller_root . '/' . $controller);
	}

	public function getRoutedQuery()
	{
		return $this->executed_query;
	}

	private function processConstructor()
	{
		if ($this->isAvailableController(self::CONSTRUCT_MAGIC_FUNCTION)) {
			$__construct = new Control(
				$this->controller_root,
				self::CONSTRUCT_MAGIC_FUNCTION,
				$this->request
			);
			$__construct_ret = $__construct->call();
			if ($__construct_ret instanceof Response) {
				$__construct_ret->send();
				exit;
			}
		}
	}

	private function processRoute()
	{
		$__route = new Route($this->controller_root);
		if ($__route->isExist()) {
			$this->remain_query = $__route->route($this->remain_query, $this->request);
		}
	}

	/**
	 * @return array
	 */
	private function parseQuery()
	{
		if (preg_match('/^\/?([\w\._]+)\/?/', $this->remain_query, $match)) {
			$matched_query_raw = $match[0];
			$matched_query = $match[1];
			$unmatched_query_tail = substr($this->remain_query, strlen($matched_query_raw));
			return array($matched_query, $unmatched_query_tail);
		} else {
			$matched_query = 'index';
			$unmatched_query_tail = '';
			return array($matched_query, $unmatched_query_tail);
		}
	}
}
