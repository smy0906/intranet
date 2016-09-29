<?php

namespace Intra\Service\Support\Column;

class SupportColumnReadonly extends SupportColumn
{
	/**
	 * SupportColumnReadonly constructor.
	 *
	 * @param $column_name
	 */
	public function __construct($column_name)
	{
		parent::__construct($column_name);
	}
}
