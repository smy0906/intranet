<?php
/** @var $this Intra\Core\Control */
use Intra\Lib\Azure\AuthorizationHelperForAADGraphService;
use Intra\Lib\Azure\GraphServiceAccessHelper;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\RedirectResponse;

$request = $this->getRequest();
$azure_login_token_array = AuthorizationHelperForAADGraphService::getAuthenticationHeaderFor3LeggedFlow($_GET['code']);
$user = GraphServiceAccessHelper::getMeEntry($azure_login_token_array);
$id = $user->mailNickname;

if (UserSession::loginByAzure($id)) {
	return new RedirectResponse('/?after_login');
} else {
	return new RedirectResponse('/users/join');
}
