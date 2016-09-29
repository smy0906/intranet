<?php

namespace Intra\Service\Support\Column;

class SupportColumnDate extends SupportColumn
{
	/**
	 * @var string
	 */
	public $default;
	/**
	 * @var
	 */
	public $is_ordering_column;
	/**
	 * @var string
	 */
	private $string;

	/**
	 * SupportColumnDate constructor.
	 *
	 * @param string $string
	 * @param string $default
	 * @param bool   $is_ordering_column
	 */
	public function __construct($string, $default = '', $is_ordering_column = false)
	{
		parent::__construct($string);
		$this->default = $default;
		$this->is_ordering_column = $is_ordering_column;
		$this->string = $string;
	}
}
