<?php
namespace Intra\Core;

class ControlRouteNull
{
	public function __call($a, $b)
	{
		return $this;
	}
}
