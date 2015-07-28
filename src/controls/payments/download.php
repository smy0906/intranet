<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserPayment;
use Intra\Service\UserSession;

if (!UserSession::getSelf()->isSuperAdmin()) {
	exit;
}

$request = $this->getRequest();
$month = $request->get('month');

$month = UserPayment::parseMonth($month);

$payment_service = new UserPayment(UserSession::getSupereditUser());
$payment_service->sendExcelResposeAndExit($month);
