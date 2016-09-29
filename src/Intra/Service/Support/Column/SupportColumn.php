<?php

namespace Intra\Service\Support\Column;

class SupportColumn
{
	public $key;

	public function __construct($column_name)
	{
		$this->key = $column_name;
	}

	public function getClass()
	{
		$class = get_called_class();
		$class = basename($class);
		return $class;
	}
}
