<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Press\Press;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$press_id = $request->get('id');
$user = UserSession::getSupereditUser();
$press_service = new Press($user);
return $press_service->del($press_id);
