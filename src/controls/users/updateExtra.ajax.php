<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserDtoHandler;

$request = $this->getRequest();

$uid = $request->get('userid');
$key = $request->get('key');
$value = $request->get('value');

$user = UserDtoHandler::importFromDatabaseWithUid($uid);
$user->setExtra($key, $value);
return 1;
