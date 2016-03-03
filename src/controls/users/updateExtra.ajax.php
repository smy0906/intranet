<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserInstanceService;

$request = $this->getRequest();

$uid = $request->get('userid');
$key = $request->get('key');
$value = $request->get('value');

$user = UserInstanceService::importFromDatabaseWithUid($uid);
$user->setExtra($key, $value);
return 1;
