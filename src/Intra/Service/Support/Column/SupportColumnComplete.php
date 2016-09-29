<?php

namespace Intra\Service\Support\Column;

class SupportColumnComplete extends SupportColumn
{
	/**
	 * @var callable $callback_has_user_auth
	 */
	public $callback_has_user_auth;

	/**
	 * SupportColumnComplete constructor.
	 *
	 * @param string   $string
	 * @param callable $callback_has_user_auth
	 */
	public function __construct($string, callable $callback_has_user_auth)
	{
		parent::__construct($string);
		$this->callback_has_user_auth = $callback_has_user_auth;
	}
}
