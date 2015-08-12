<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 1. 7
 * Time: 오후 7:38
 */

namespace Intra\Core;

class ControlRouteNull
{
	public function __call($a, $b)
	{
		return $this;
	}
}
