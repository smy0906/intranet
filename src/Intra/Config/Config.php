<?php

namespace Intra\Config;

use Intra\Core\ConfigLoader;

class Config extends ConfigLoader
{
	public static $upload_dir;

	public static $mysql_host;
	public static $mysql_user;
	public static $mysql_password;
	public static $mysql_db;

	public static $sentry_key;
	public static $sentry_public_key;

	public static $domain = "ridi.com";

	public static $is_dev = false;
}
