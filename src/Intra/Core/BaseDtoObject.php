<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-02-18
 * Time: 오후 1:58
 */

namespace Intra\Core;


use Exception;

class BaseDtoObject
{
	protected $dto;

	protected function __construct($dto)
	{
		$this->dto = $dto;
	}

	public static function importFromDto($dto)
	{
		$called_class = get_called_class();
		return new $called_class($dto);
	}

	public static function exportToDto($dto)
	{
		return clone($dto);
	}

	protected static function assertDatabaseRowExist($row)
	{
		if (!is_array($row) || count($row) == 0) {
			throw new Exception("Database Row Not Exist");
		}
	}
}
