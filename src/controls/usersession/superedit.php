<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\Users;
use Intra\Service\User\UserSession;

$self = UserSession::getSelf();
if (!$self->isSuperAdmin()) {
	return '권한이 없습니다';
}

$uid = $this->getRequest()->get('uid');
if (!Users::isExistByUid($uid)) {
	return '해당 유저가 없습니다.';
}

UserSession::setSupereditUser($uid);
return 1;
