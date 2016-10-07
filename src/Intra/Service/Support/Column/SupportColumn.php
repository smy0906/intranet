<?php

namespace Intra\Service\Support\Column;

class SupportColumn
{
	public $key;
	public $class_name;

	public function __construct($column_name)
	{
		$this->key = $column_name;
		$class_name = preg_replace('/\w+\\\\/', '', get_called_class());
		$this->class_name = $class_name;
	}
}
