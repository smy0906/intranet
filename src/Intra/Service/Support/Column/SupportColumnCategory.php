<?php

namespace Intra\Service\Support\Column;

class SupportColumnCategory extends SupportColumn
{
	/**
	 * @var array
	 */
	public $category_items;

	/**
	 * SupportColumnCategory constructor.
	 *
	 * @param string $string
	 * @param array  $category_items
	 */
	public function __construct($string, $category_items)
	{
		parent::__construct($string);
		$this->category_items = $category_items;
	}
}
