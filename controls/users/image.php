<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserEditService;

$request = $this->getRequest();
$uid = $request->get('uid');

return UserEditService::getImage($uid);
