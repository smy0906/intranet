<?php

namespace Intra\Service\Support\Column;

class SupportColumnMutual extends SupportColumn
{
	public $groups;

	/**
	 * SupportColumnMutual constructor.
	 *
	 * @param $column
	 * @param $groups
	 */
	public function __construct($column, $groups)
	{
		parent::__construct($column);
		$this->groups = $groups;
	}

	public function getRemainColumnExceptOneGroup($value)
	{
		$groups = $this->groups;
		unset($groups[$value]);
		$remain_columns = array_flatten($groups);
		return $remain_columns;
	}
}
