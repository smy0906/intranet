<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserEditService;

$request = $this->getRequest();
$uid = $request->get('uid');
$key = $request->get('key');
$value = $request->get('value');

return UserEditService::updateInfo($uid, $key, $value);
