<?php
/** @var $this Intra\Core\Control */

use Intra\Model\UserModel;
use Intra\Service\User\UserSession;

$self = UserSession::getSelfDto();
if (!$self->is_admin) {
	return '권한이 없습니다';
}

$uid = $this->getRequest()->get('uid');
if (!UserModel::isExistByUid($uid)) {
	return '해당 유저가 없습니다.';
}

UserSession::setSupereditUser($uid);
return 1;
