<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserDtoObject;

$request = $this->getRequest();

$uid = $request->get('userid');
$key = $request->get('key');
$value = $request->get('value');

$user = UserDtoObject::importFromDatabaseWithUid($uid);
$user->setExtra($key, $value);
return 1;
