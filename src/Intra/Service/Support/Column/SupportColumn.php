<?php

namespace Intra\Service\Support\Column;

class SupportColumn
{
	public $key;
	public $class_name;

	public function __construct($column_name)
	{
		$this->key = $column_name;
		$this->class_name = basename(get_called_class());
	}
}
