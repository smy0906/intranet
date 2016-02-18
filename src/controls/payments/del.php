<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPayment;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$paymentid = $request->get('paymentid');

$payment_service = new UserPayment(UserSession::getSupereditUser());
return $payment_service->del($paymentid);
