<?php

namespace Intra\Service\Support\Column;

class SupportColumnText extends SupportColumn
{
	/**
	 * @var string
	 */
	public $default;
	/**
	 * @var string
	 */
	public $string;
	/**
	 * @var string
	 */
	public $placeholder;

	/**
	 * SupportColumnText constructor.
	 *
	 * @param string $string
	 * @param string $default
	 * @param string $place_holder
	 */
	public function __construct($string, $default = '', $place_holder = '')
	{
		parent::__construct($string);
		$this->default = $default;
		$this->placeholder = $place_holder;
	}
}
