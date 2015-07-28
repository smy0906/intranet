<?php
/** @var $this Intra\Core\Control */

require_once(__DIR__ . '/../../azure/AuthorizationHelperForGraph.php');

$azure_login = AuthorizationHelperForAADGraphService::getAuthorizatonURL();

return compact('azure_login');