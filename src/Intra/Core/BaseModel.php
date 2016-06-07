<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-02-18
 * Time: 오후 12:37
 */

namespace Intra\Core;


use Intra\Service\IntraDb;

class BaseModel
{
	/**
	 * @return \Gnf\db\base
	 */
	protected static function getDb()
	{
		return IntraDb::getGnfDb();
	}

	public static function transactional($function)
	{
		return IntraDb::getGnfDb()->transactional($function);
	}
}
