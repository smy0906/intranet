<?php

namespace Intra\Service\Support;

use Intra\Core\MsgException;
use Intra\Service\Support\Column\SupportColumn;
use Intra\Service\Support\Column\SupportColumnCategory;
use Intra\Service\Support\Column\SupportColumnDate;
use Intra\Service\Support\Column\SupportColumnRegisterUser;
use Intra\Service\Support\Column\SupportColumnTeam;
use Intra\Service\Support\Column\SupportColumnText;
use Symfony\Component\HttpFoundation\Request;

class SupportDto
{
	public $target;
	public $columns;

	/**
	 * view only
	 */
	public $id;
	public $uid;

	/**
	 * @param Request         $request
	 * @param int             $uid
	 * @param SupportColumn[] $columns
	 *
	 * @return SupportDto
	 * @throws MsgException
	 */
	public static function importFromAddRequest($request, $uid, $columns)
	{
		$dto = new self;
		$dto->target = $request->get('target');
		$dto->columns = [];

		foreach ($columns as $column_name => $column) {
			if ($column instanceof SupportColumnCategory
				|| $column instanceof SupportColumnTeam
				|| $column instanceof SupportColumnText
				|| $column instanceof SupportColumnDate
			) {
				$key = $column->key;
				$value = $request->get($key);
				$dto->columns[$key] = $value;
			} elseif ($column instanceof SupportColumnRegisterUser) {
				$key = $column->key;
				$value = $uid;
				$dto->columns[$key] = $value;
			}
		}
		return $dto;
	}

	/**
	 * @param string          $target
	 * @param SupportColumn[] $columns
	 * @param array           $dict
	 *
	 * @return SupportDto
	 */
	public static function importFromDict($target, $columns, $dict)
	{
		$dto = new self;
		$dto->id = $dict['id'];
		$dto->target = $target;
		$dto->columns = $dict;

		foreach ($columns as $column_name => $column) {
			if ($column instanceof SupportColumnRegisterUser) {
				$key = $column->key;
				$dto->uid = $dict[$key];
			}
		}
		return $dto;
	}

	public function exportDictAddRequest()
	{
		$dict = [];
		foreach ($this->columns as $key => $column) {
			$dict[$key] = $column;
		}
		return $dict;
	}
}
