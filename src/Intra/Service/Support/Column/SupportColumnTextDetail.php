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
	public $parent_column_value;

	/**
	 * SupportColumnTextDetail constructor.
	 *
	 * @param string $string
	 * @param string $parent_column
	 * @param string $parent_column_value
	 */
	public function __construct($string, $parent_column, $parent_column_value)
	{
		parent::__construct($string);
		$this->parent_column = $parent_column;
		$this->parent_column_value = $parent_column_value;
	}
}
