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
$page = $request->get('page');
$items_per_page = $request->get('items_per_page');
$user = UserSession::getSupereditUser();

$press_service = new Press($user);

return $request->query->get('callback') . '(' . $press_service->getPressByPage($page, $items_per_page) . ')';
