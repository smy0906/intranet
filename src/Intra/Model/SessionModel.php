<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 13. 12. 20
 * Time: 오전 11:55
 */

namespace Intra\Model;

class SessionModel
{
	public function __construct($other_db = null)
	{
		if (!isset($_SESSION)) {
			ini_set("session.gc_maxlifetime", 60 * 60 * 24 * 10);
			session_set_cookie_params(time() + 60 * 60 * 24 * 30);
			session_start();
		}
	}

	public function get($string)
	{
		return $_SESSION[$string];
	}

	public function set($key, $value)
	{
		return $_SESSION[$key] = $value;
	}
}
