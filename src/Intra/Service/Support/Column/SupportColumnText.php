<?php

namespace Intra\Service\Support\Column;

class SupportColumnText extends SupportColumn
{
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
		parent::placeholder($place_holder);
		parent::defaultValue($default);
	}
}
