<?php

namespace Intra\Core;

use Intra\Config\Config;

class ConfigLoader
{
	public static function loadIfExist($config_file)
	{
		if (!is_readable($config_file)) {
			return;
		}
		require_once($config_file);
		$config_basename = basename($config_file);
		$config_basename = preg_replace('/\.php$/i', '', $config_basename);
		if (class_exists($config_basename)) {
			$config_class = new \ReflectionClass(Config::class);
			$custom_class = new \ReflectionClass($config_basename);
			$custom_propertys = $custom_class->getStaticProperties();
			foreach ($custom_propertys as $key => $value) {
				$config_class->setStaticPropertyValue($key, $value);
			}
		}
	}
}
