<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserDtoFactory;
use Intra\Model\UserModel;

$request = $this->getRequest();
$uid = $request->get('uid');
$key = $request->get('key');
$value = $request->get('value');

$user_dto = UserDtoFactory::createByUid($uid);
if ($user_dto === null) {
	return '오류';
}

UserModel::update($uid, [$key => $value]);

$user_dto = UserDtoFactory::createByUid($uid);
return $user_dto->$key;
