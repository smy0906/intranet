<?php

namespace Intra\Lib\Azure;

use Intra\Config\Config;

class Settings
{
	private static $clientId =
		array(
			'intra.ridi.com' => '***REMOVED***',
			'intra.studiod.co.kr' => '***REMOVED***'
		);
	private static $password =
		array(
			'intra.ridi.com' => 'KpZt7XrwDuJw2/I/NTHDCbQZ6jmrtbcUefZta4Lv90w=',
			'intra.studiod.co.kr' => '***REMOVED***'
		);
	private static $redirectURI =
		array(
			'intra.ridi.com' => 'http://intra.ridi.com/usersession/login.azure',
			'intra.studiod.co.kr' => 'http://intra.studiod.co.kr/usersession/login.azure'
		);
	private static $resourceURI =
		array(
			'intra.ridi.com' => 'https://graph.windows.net',
			'intra.studiod.co.kr' => 'https://graph.windows.net'
		);
	private static $appTenantDomainName =
		array(
			'intra.ridi.com' => 'ridicorp.com',
			'intra.studiod.co.kr' => 'studiod.co.kr'
		);
	private static $apiVersion =
		array(
			'intra.ridi.com' => 'api-version=2013-11-08',
			'intra.studiod.co.kr' => 'api-version=2013-11-08'
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
