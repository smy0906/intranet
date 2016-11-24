<?php

namespace Intra\Service\Support\Column;

class SupportColumnTextDetail extends SupportColumn
{
	/**
	 * @var string
	 */
	public $parent_column;
	/**
	 * @var string
	 */
	public $parent_column_values;

	/**
	 * initSupportColumnTextDetail constructor.
	 *
	 * @param string   $string
	 * @param string   $parent_column
	 * @param string[] $parent_column_values
	 * @param string   $placeholder
	 */
	public function __construct($string, $parent_column, $parent_column_values, $placeholder = '')
	{
		parent::__construct($string);
		parent::placeholder($placeholder);
		$this->parent_column = $parent_column;
		$this->parent_column_values = $parent_column_values;
	}
}
