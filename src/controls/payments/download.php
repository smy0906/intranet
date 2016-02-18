<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPayment;
use Intra\Service\Payment\UserPaymentStat;
use Intra\Service\User\UserSession;

if (!UserSession::getSelf()->isSuperAdmin()) {
	exit;
}

$request = $this->getRequest();
$month = $request->get('month');

$month = UserPayment::parseMonth($month);

$payment_service = new UserPaymentStat();
$payment_service->sendExcelResposeAndExit($month);
