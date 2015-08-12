<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-27
 * Time: 오후 9:57
 */

namespace Intra\Service;

use Gnf\db\base;
use Gnf\db\PDO;
use Illuminate\Database\Capsule\Manager as Capsule;
use Intra\Config\Config;

class IntraDb
{
	private static $gnfdb;

	public static function bootDB()
	{
		if (self::$gnfdb === null) {
			$capsule = new Capsule;
			$capsule->addConnection(
				[
					'driver' => 'mysql',
					'host' => Config::$mysql_host,
					'database' => Config::$mysql_db,
					'username' => Config::$mysql_user,
					'password' => Config::$mysql_password,
					'charset' => 'utf8',
					'collation' => 'utf8_unicode_ci',
					'prefix' => '',
				]
			);
			$capsule->setAsGlobal();
			$capsule->bootEloquent();

			$db = new PDO($capsule->getConnection()->getPdo());
			self::$gnfdb = $db;
		}
	}

	/**
	 * @return base
	 */
	public static function getGnfDb()
	{
		self::bootDB();
		return self::$gnfdb;
	}
}
