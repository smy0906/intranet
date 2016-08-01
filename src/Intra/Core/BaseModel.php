<?php
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
