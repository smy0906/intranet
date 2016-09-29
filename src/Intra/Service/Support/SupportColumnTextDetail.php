<?php

namespace Intra\Service\Support;

use Intra\Service\Support\Column\SupportColumn;

class SupportColumnTextDetail extends SupportColumn
{
	/**
	 * @var string
	 */
	public $parent_column;
	/**
	 * @var string
	 */
	public $prent_column_value;

	/**
	 * SupportColumnTextDetail constructor.
	 *
	 * @param string $string
	 * @param string $parent_column
	 * @param string $prent_column_value
	 */
	public function __construct($string, $parent_column, $prent_column_value)
	{
		parent::__construct($string);
		$this->parent_column = $parent_column;
		$this->prent_column_value = $prent_column_value;
	}
}
