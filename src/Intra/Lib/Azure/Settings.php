<?php

namespace Intra\Lib\Azure;

use Intra\Config\Config;

class Settings
{
	private static $clientId =
		array(
			'ridi.com' => '***REMOVED***',
			'studiod.co.kr' => '***REMOVED***'
		);
	private static $password =
		array(
			'ridi.com' => 'KpZt7XrwDuJw2/I/NTHDCbQZ6jmrtbcUefZta4Lv90w=',
			'studiod.co.kr' => '***REMOVED***'
		);
	private static $redirectURI =
		array(
			'ridi.com' => 'http://intra.ridi.com/usersession/login.azure',
			'studiod.co.kr' => 'http://intra.studiod.co.kr/usersession/login.azure'
		);
	private static $resourceURI =
		array(
			'ridi.com' => 'https://graph.windows.net',
			'studiod.co.kr' => 'https://graph.windows.net'
		);
	private static $appTenantDomainName =
		array(
			'ridi.com' => 'ridicorp.com',
			'studiod.co.kr' => 'studiod.co.kr'
		);
	private static $apiVersion =
		array(
			'ridi.com' => 'api-version=2013-11-08',
			'studiod.co.kr' => 'api-version=2013-11-08'
		);

	public static function getClientId()
	{
		$domain = self::getDomain();
		return self::$clientId[$domain];
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

	/**
	 * @return string
	 */
	private static function getDomain()
	{
		$domain = Config::$domain;
		return $domain;
	}
}
