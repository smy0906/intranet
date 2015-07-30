<?php
/** @var $this Intra\Core\Control */
use Intra\Service\UserSession;
use Symfony\Component\HttpFoundation\RedirectResponse;

$request = $this->getRequest();
require_once(__DIR__ . '/../../azure/AuthorizationHelperForGraph.php');
require_once(__DIR__ . '/../../azure/GraphServiceAccessHelper.php');

$azure_login_token_array = AuthorizationHelperForAADGraphService::getAuthenticationHeaderFor3LeggedFlow($_GET['code']);

$user = GraphServiceAccessHelper::getMeEntry($azure_login_token_array);
$id = $user->mailNickname;

if (UserSession::loginByAzure($id)) {
	return new RedirectResponse('/?after_login');
} else {
	return new RedirectResponse('/users/join');
}
