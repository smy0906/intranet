<?php
/** @var $this Intra\Core\Control */

use Intra\Lib\Azure\AuthorizationHelperForAADGraphService;

$azure_login = AuthorizationHelperForAADGraphService::getAuthorizatonURL();

return compact('azure_login');
