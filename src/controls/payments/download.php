<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPaymentRequestFilter;
use Intra\Service\Payment\UserPaymentService;
use Intra\Service\Payment\UserPaymentStatService;
use Intra\Service\User\UserSession;

if (!UserSession::getSelfDto()->is_admin) {
	exit;
}

$request = $this->getRequest();
$month = $request->get('month');

$month = UserPaymentRequestFilter::parseMonth($month);

$payment_service = new UserPaymentStatService();
$payment_service->sendExcelResposeAndExit($month);
