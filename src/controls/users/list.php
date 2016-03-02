<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

$self = UserSession::getSelfDto();
if (!$self->is_admin) {
	return '권한이 없습니다';
}

return ['users' => UserService::getAllUserDtos()];
