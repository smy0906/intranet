<?php
namespace Gnf {
	class __gnfCache
	{
		var $timeoutMin;

		function __construct($dbObject, $timeoutMin)
		{
			$this->obj = $dbObject;
			$this->timeoutMin = $timeoutMin;
		}

		function __call($method, $args)
		{
			$key = $this->getKey($method, $args);

			$result = false;
			$cache = $this->getCache($key, $result);
			if ($result == true) {
				return $cache;
			}

			$dat = call_user_func_array(array($this->obj, $method), $args);

			if ($dat === null
				|| $dat === false
				|| (is_array($dat) && count($dat) == 0)
			) {
				return $dat;
			}
			$this->setCache($key, $dat);
			return $dat;
		}

		function getKey($method, $args)
		{
			$key = sha1($method . serialize($args));
			$dir = sys_get_temp_dir() . '/gnfCache';
			$file = $dir . '/' . $key;
			return $file;
		}

		function getCache($key, &$resultReturn)
		{
			$dir = dirname($key);
			if (!is_dir($dir)) {
				@mkdir($dir, 0777, true);
			}
			if (!is_file($key)) {
				return null;
			}
			$diff = time() - filemtime($key);
			if ($diff >= $this->timeoutMin * 60) {
				return null;
			}
			$ret = file_get_contents($key);
			if ($ret === false) {
				return null;
			}
			$resultReturn = true;
			return unserialize($ret);
		}

		function setCache($key, $dat)
		{
			//윈도우에서는 안되네
			//if(!is_writable($key))
			//return false;

			//@file_put_contents($key, serialize($dat), LOCK_EX);
			$tmp_file = tempnam(dirname($key), basename($key));
			if (false !== @file_put_contents($tmp_file, serialize($dat))) {
				if (@rename($tmp_file, $key)) {
					@chmod($key, 0666 & ~umask());
					return;
				}
			}
		}
	}
}

namespace {
	/**
	 * @param stdClass $dbObject
	 * @param int $timeoutMinute
	 * @return stdClass
	 */
	function gnfCache($dbObject, $timeoutMinute = 10)
	{
		return new Gnf\__gnfCache($dbObject, $timeoutMinute);
	}
}

