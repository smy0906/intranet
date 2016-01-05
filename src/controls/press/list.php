<?php
/**
 * Created by PhpStorm.
 * User: KHS
 * Date: 2016. 1. 5.
 * Time: 오전 10:11
 */

use Intra\Service\Press\Press;
use Intra\Service\UserSession;

$request = $this->getRequest();
$user = UserSession::getSupereditUser();

$press_service = new Press($user);

return $_GET['callback'].'('.$press_service->getListByJson().')';
