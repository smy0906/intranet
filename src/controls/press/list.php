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
$ITEMS_PER_PAGE = $request->get('items');
$user = UserSession::getSupereditUser();

$press_service = new Press($user);

return $request->query->get('callback') . '(' . $press_service->getPressByPage($page, $ITEMS_PER_PAGE) . ')';
