<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-21
 * Time: 오후 6:44
 */

namespace Intra\Model\Base;

/**
 * Class DomainCacheModel
 * @package Intra\Model
 *
 * 모델 객체에대해 pk 기준으로 캐시를 보장하기위한 시스템
 *
 */

class DomainCacheModel
{
	static private $cacheExist = array();
	static private $cache = array();

	/**
	 * @param $domain_primary_keys array|string
	 * @param $closure callable
	 * @return mixed
	 */
	protected static function setCache($domain_primary_keys, $closure)
	{
		$class = get_called_class();
		if (!is_array($domain_primary_keys)) {
			$domain_primary_keys = array($domain_primary_keys);
		}

		self::cacheInit($class);

		$cache = self::findCacheByKey($class, $domain_primary_keys);
		if ($cache !== null) {
			return $cache;
		}
		$value = $closure();
		self::insertCache($class, $domain_primary_keys, $value);
		return $value;
	}

	private static function cacheInit($class)
	{
		if (!is_array(self::$cacheExist[$class])) {
			self::$cacheExist[$class] = array();
		}
		if (!is_array(self::$cache[$class])) {
			self::$cache[$class] = array();
		}
	}

	private static function findCacheByKey($class, $domain_primary_keys)
	{
		$cursor = self::$cacheExist[$class];
		$common = array_intersect(array_keys($cursor), $domain_primary_keys);
		if (count($common) == count($domain_primary_keys)) {
			sort($domain_primary_keys);
			$compiled_cache_keys = implode('#', $domain_primary_keys);
			return self::$cache[$class][$compiled_cache_keys];
		}
		return null;
	}

	private static function insertCache($class, $domain_primary_keys, $value)
	{
		foreach ($domain_primary_keys as $cache_key) {
			self::$cacheExist[$class][$cache_key] = true;
		}
		sort($domain_primary_keys);
		$compiled_cache_keys = implode('#', $domain_primary_keys);
		self::$cache[$class][$compiled_cache_keys] = $value;
	}

	protected static function invalidateCache($domain_primary_keys)
	{
		$class = get_called_class();
		if (!is_array($domain_primary_keys)) {
			$domain_primary_keys = array($domain_primary_keys);
		}
		foreach ($domain_primary_keys as $cache_key) {
			unset(self::$cacheExist[$class][$cache_key]);
		}
	}
}
