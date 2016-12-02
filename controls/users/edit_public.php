<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserDtoFactory;
use Intra\Service\User\UserDtoHandler;
use Intra\Service\User\UserJoinService;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$uid = $request->get('uid');
$key = $request->get('key');
$value = $request->get('value');

$user_dto = UserDtoFactory::createByUid($uid);
if ($user_dto === null) {
	return '오류';
}

$user = new UserDtoHandler(UserDtoFactory::createByUid($uid));
$user->updateByKey($key, $value);

$user_dto = UserDtoFactory::createByUid($uid);

return $user_dto->$key;
