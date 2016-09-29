<?php

namespace Intra\Service\Support\Column;

use Intra\Core\MsgException;
use Intra\Service\Support\SupportDto;
use Intra\Service\Support\SupportModel;
use Intra\Service\Support\SupportPolicy;

class SupportDtoFactory
{
	public static function get($target, $id)
	{
		$dict = SupportModel::getDict($target, $id);
		$columns = SupportPolicy::getColumns($target);
		if ($dict === null) {
			throw  new MsgException('해당 자료가 없습니다.');
		}
		$dto = SupportDto::importFromDict($target, $columns, $dict);
		return $dto;
	}
}
