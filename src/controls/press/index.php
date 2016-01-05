<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Press\Press;
use Intra\Service\UserSession;

$request = $this->getRequest();
$user = UserSession::getSupereditUser();

$press_service = new Press($user);
return $press_service->index();
