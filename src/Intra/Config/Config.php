<?php

namespace Intra\Config;

use Intra\Core\ConfigLoader;

class Config
{
	use ConfigLoader;

	public static $upload_dir;

	public static $mysql_host;
	public static $mysql_user;
	public static $mysql_password;
	public static $mysql_db;

	public static $sentry_key;
	public static $sentry_public_key;

	public static $domain = "ridi.com";

	public static $is_dev = false;
	public static $test_mail = '';

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
}
