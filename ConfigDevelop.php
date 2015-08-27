<?php

use Intra\Config\Config;

class ConfigDevelop extends Config
{
	public static $upload_dir = __DIR__;

	public static $mysql_host = '192.168.0.205';
	public static $mysql_user = 'ridi_test';
	public static $mysql_password = 'ridi_test^&*$#^&*';
	public static $mysql_db = 'ridi_test';

	public static $is_dev = true;
}

ConfigDevelop::$upload_dir = __DIR__ . '/upload/';
