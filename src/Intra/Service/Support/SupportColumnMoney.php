<?php

namespace Intra\Service\Support;

use Intra\Service\Support\Column\SupportColumn;

class SupportColumnMoney extends SupportColumn
{
	/**
	 * SupportColumnMoney constructor.
	 *
	 * @param string $string
	 */
	public function __construct($string)
	{
		parent::__construct($string);
	}
}
