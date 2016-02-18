<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

$self = UserSession::getSelfDto();
$replaceable = UserPolicy::is_first_page_replaceable($self);

return [
	'replaceable' => $replaceable,
	'users' => UserService::getAvailableUserDtos(),
	'allUsers' => UserService::getAllUserDtos(),
	'allCurrentUsers' => UserService::getAvailableUserDtos()
];
