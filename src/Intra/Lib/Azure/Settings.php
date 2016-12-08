<?php
//
//namespace Intra\Lib\Azure;
//
//use Intra\Config\Config;
//
//class Settings
//{
//	public static function getClientId()
//	{
//		$domain = self::getDomain();
//		return Config::$azure['clientId'][$domain];
//	}
//
//	/**
//	 * @return string
//	 */
//	private static function getDomain()
//	{
//		$domain = Config::$domain;
//		return $domain;
//	}
//
//	public static function getPassword()
//	{
//		$domain = self::getDomain();
//		return Config::$azure['password'][$domain];
//	}
//
//	public static function getRediectURI()
//	{
//		$domain = self::getDomain();
//		return Config::$azure['redirectURI'][$domain];
//	}
//
//	public static function getResourceURI()
//	{
//		$domain = self::getDomain();
//		return Config::$azure['resourceURI'][$domain];
//	}
//
//	public static function getAppTenantDomainName()
//	{
//		$domain = self::getDomain();
//		return Config::$azure['$appTenantDomainName'][$domain];
//	}
//
//	public static function getApiVersion()
//	{
//		$domain = self::getDomain();
//		return Config::$azure['$apiVersion'][$domain];
//	}
//}

namespace Intra\Lib\Azure;

use Intra\Config\Config;

class Settings
{
	private static $clientId = [
		'ridi.com' => '***REMOVED***',
		'studiod.co.kr' => '***REMOVED***'
	];
	private static $password = [
		'ridi.com' => '***REMOVED***',
		'studiod.co.kr' => '***REMOVED***'
	];
	private static $redirectURI = [
		'ridi.com' => 'http://intra.ridi.com/usersession/login.azure',
		'studiod.co.kr' => 'http://intra.studiod.co.kr/usersession/login.azure'
	];
	private static $resourceURI = [
		'ridi.com' => 'https://graph.windows.net',
		'studiod.co.kr' => 'https://graph.windows.net'
	];
	private static $appTenantDomainName = [
		'ridi.com' => 'ridicorp.com',
		'studiod.co.kr' => 'studiod.co.kr'
	];
	private static $apiVersion = [
		'ridi.com' => 'api-version=2013-11-08',
		'studiod.co.kr' => 'api-version=2013-11-08'
	];

	public static function getClientId()
	{
		$domain = self::getDomain();
		return self::$clientId[$domain];
	}

	/**
	 * @return string
	 */
	private static function getDomain()
	{
		$domain = Config::$domain;
		return $domain;
	}

	public static function getPassword()
	{
		$domain = self::getDomain();
		return self::$password[$domain];
	}

	public static function getRediectURI()
	{
		$domain = self::getDomain();
		return self::$redirectURI[$domain];
	}

	public static function getResourceURI()
	{
		$domain = self::getDomain();
		return self::$resourceURI[$domain];
	}

	public static function getAppTenantDomainName()
	{
		$domain = self::getDomain();
		return self::$appTenantDomainName[$domain];
	}

	public static function getApiVersion()
	{
		$domain = self::getDomain();
		return self::$apiVersion[$domain];
	}
}
