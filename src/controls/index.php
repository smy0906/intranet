<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

$self = UserSession::getSelfDto();
if ($self->is_admin) {
	$replaceable = true;
}

return [
	'replaceable' => $replaceable,
	'users' => UserService::getAvailableUserDtos(),
	'allUsers' => UserService::getAllUserDtos(),
	'allCurrentUsers' => UserService::getAvailableUserDtos()
];
