<?php
namespace Intra\Service;

use Intra\Config\Config;
use Raven_Autoloader;
use Raven_Client;
use Raven_ErrorHandler;

class Ridi
{
	/**
	 * @var Raven_Client
	 */
	private static $raven_client;

	public static function isRidiIP()
	{
		/*
		 * 정규식으로 되어있으니 찾기 힘들어서 주석을 추가
		 * ***REMOVED*** (KIDC NAS VPN)
		 * ***REMOVED*** (어반벤치 #1)
		 * ***REMOVED*** (어반벤치 #2)
		 * ***REMOVED*** (어반벤치 #3)
		 * ***REMOVED*** (어반벤치 #4)
		 */
		$ridi_ips = [
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',

			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***'
		];
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
		self::$raven_client = new Raven_Client($sentry_key);
		$error_handler = new Raven_ErrorHandler(self::$raven_client);
		$error_handler->registerExceptionHandler();
		$error_handler->registerErrorHandler(true, E_ALL & ~E_NOTICE & ~E_STRICT);
		$error_handler->registerShutdownFunction();
	}

	public static function triggerSentryException(\Exception $e)
	{
		if (self::$raven_client instanceof Raven_Client) {
			self::$raven_client->captureException($e);
			return true;
		}
		return false;
	}
}
