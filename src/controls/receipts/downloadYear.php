<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPaymentRequestFilter;
use Intra\Service\Receipt\UserReceiptsStat;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserSession;

if (UserPolicy::isReceiptsAdmin(UserSession::getSelfDto())) {
	exit;
}

$request = $this->getRequest();
$month = $request->get('month');

$month = UserPaymentRequestFilter::parseMonth($month);

$payment_service = new UserReceiptsStat();
$payment_service->downloadYear($month);
