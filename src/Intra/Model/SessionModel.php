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
	public function __construct()
	{
		self::init();
	}

	public static function init()
	{
		if (!isset($_SESSION)) {
			$session_lifetime = 60 * 60 * 24 * 7;//7일
			ini_set("session.gc_maxlifetime", $session_lifetime);
			session_set_cookie_params($session_lifetime);
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
