<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-17
 * Time: 오후 5:35
 */

namespace Intra\Service;

use Intra\Config\Config;
use Raven_Autoloader;
use Raven_Client;
use Raven_ErrorHandler;

class Ridi
{

	public static function isRidiIP()
	{
		/*
		 * 정규식으로 되어있으니 찾기 힘들어서 주석을 추가
		 * 112.220.140.68
		 * 112.220.140.69
		 * 112.220.140.70
		 * 115.93.188.46
		 * 106.247.248.130
		 */
		$ridi_ips = array(
			'***REMOVED***',
			'/^10\.10\.0\.[0-9]+/',
			'/^192\.168\.0\.[0-9]+/',
			'/^192\.168\.1\.[0-9]+/',
			'/^112\.220\.199\.134/',
			'/^112\.220\.140\.66/',
			'/^112\.220\.140\.68/',
			'/^112\.220\.140\.69/',
			'/^112\.220\.140\.70/',
			'/^115\.93\.188\.46/',
			'/^106\.247\.248\.130/',
			'***REMOVED***',
			'***REMOVED***'
		);
		foreach ($ridi_ips as $pattern) {
			if (preg_match($pattern, $_SERVER['REMOTE_ADDR'])) {
				return true;
			}
		}

		return false;
	}

	public static function enableSentry()
	{
		$sentry_key = strval(Config::$sentry_key);
		if (strlen($sentry_key) <= 0) {
			return;
		}
		Raven_Autoloader::register();
		$client = new Raven_Client($sentry_key);
		$error_handler = new Raven_ErrorHandler($client);
		$error_handler->registerExceptionHandler();
		$error_handler->registerErrorHandler(true, E_ALL & ~E_NOTICE & ~E_STRICT);
		$error_handler->registerShutdownFunction();
	}
}
