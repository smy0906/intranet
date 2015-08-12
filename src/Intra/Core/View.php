<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 13. 12. 23
 * Time: 오후 6:29
 */

namespace Intra\Core;

use Twig_Environment;
use Twig_Loader_Filesystem;

class View
{
	private $root;
	private $query;

	public function __construct($root, $query)
	{
		$this->root = $root;
		$this->query = $query;
		$this->query = preg_replace('/\/+$/', '', $this->query);
		$file = $this->root . '/' . $this->query . ".twig";
		if (file_exists($file)) {
			$this->target = $file;
		} else {
			$this->target = null;
		}
	}

	public function isExist()
	{
		return !is_null($this->target);
	}

	public function act($array)
	{
		$loader = new Twig_Loader_Filesystem($this->root);
		$twig = new Twig_Environment($loader, array());
		#$twig->addFilter('number_format', new Twig_Filter_Function('number_format'));

		echo $twig->render($this->query . '.twig', $array);
	}
}
