<?php
/** @var $this Intra\Core\Control */

use Intra\Config\Config;
use Intra\Lib\Azure\AuthorizationHelperForAADGraphService;
use Intra\Service\User\UserSession;

$azure_login = AuthorizationHelperForAADGraphService::getAuthorizatonURL();

if (Config::$is_dev) {
	UserSession::loginByAzure('test');
	$azure_login = '/';
}

return compact('azure_login');
