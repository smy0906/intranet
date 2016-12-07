<?php

use Intra\Config\Config;

class ConfigDevelop extends Config
{
	public static $upload_dir = __DIR__;

	public static $mysql_host = 'intra.ridi.com';
	public static $mysql_user = 'ridi_test';
	public static $mysql_password = 'ridi_test^&*$#^&*';
	public static $mysql_db = 'ridi_test';

	public static $is_dev = true;

	/*
	 * 정규식으로 되어있으니 찾기 힘들어서 주석을 추가
	 * ***REMOVED*** (KIDC NAS VPN)
	 * ***REMOVED*** (어반벤치 #1)
	 * ***REMOVED*** (어반벤치 #2)
	 * ***REMOVED*** (어반벤치 #3)
	 * ***REMOVED*** (어반벤치 #4)
	 */
	public static $ridi_ips = [
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

	public static $azure = [
		'clientId' => [
			'ridi.com' => '***REMOVED***',
			'studiod.co.kr' => '***REMOVED***'
		],
		'password' => [
			'ridi.com' => '***REMOVED***',
			'studiod.co.kr' => '***REMOVED***'
		],
		'redirectURI' => [
			'ridi.com' => 'http://intra.ridi.com/usersession/login.azure',
			'studiod.co.kr' => 'http://intra.studiod.co.kr/usersession/login.azure'
		],
		'resourceURI' => [
			'ridi.com' => 'https://graph.windows.net',
			'studiod.co.kr' => 'https://graph.windows.net'
		],
		'appTenantDomainName' => [
			'ridi.com' => 'ridicorp.com',
			'studiod.co.kr' => 'studiod.co.kr'
		],
		'apiVersion' => [
			'ridi.com' => 'api-version=2013-11-08',
			'studiod.co.kr' => 'api-version=2013-11-08'
		]
	];

	public static $recipients = [
		'payment' => [
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***'
		],
		'payment_admin' => [
			'***REMOVED***',
			'***REMOVED***'
		],
		'holiday' => [
			'***REMOVED***',
			'***REMOVED***'
		]
	];

	public static $user_policy = [
		'first_page_editable' => [
			'***REMOVED***',
			'***REMOVED***',
		],
		'holiday_editable' => [
			'***REMOVED***',
		],
		'press_manager' => [
			'***REMOVED***',
			'***REMOVED***',
		],
		'user_manager' => [
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
		],
		'post_admin' => [
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
		],
		'payment_admin' => [
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
		],
		'receipts_admin' => [
			'***REMOVED***',
			'***REMOVED***',
			'***REMOVED***',
		]
	];

	public static $mailgun_api_key = "***REMOVED***";
	public static $mailgun_from = "***REMOVED***";
}

ConfigDevelop::$upload_dir = __DIR__ . '/upload/';
