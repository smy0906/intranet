<?php
namespace Intra\Service\Support;

use Intra\Service\Support\Column\SupportColumn;

class SupportColumnWorker extends SupportColumn
{
	/**
	 * SupportColumnWorker constructor.
	 *
	 * @param string $string
	 */
	public function __construct($string)
	{
		parent::__construct($string);
	}
}
