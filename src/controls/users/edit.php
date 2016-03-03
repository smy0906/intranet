<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserInstanceService;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$uid = $request->get('uid');
$key = $request->get('key');
$value = $request->get('value');

if (!UserSession::isUserManager()) {
	return '권한이 없습니다';
}

$user_dto = UserService::getDtobyUid($uid);
if ($user_dto === null) {
	return '오류';
}

$user = UserInstanceService::importFromDatabaseWithUid($uid);
$user->updateByKey($key, $value);

$user_dto = UserService::getDtobyUid($uid);

return $user_dto->$key;
