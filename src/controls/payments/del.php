<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserPayment;
use Intra\Service\UserSession;

$request = $this->getRequest();
$paymentid = $request->get('paymentid');

$payment_service = new UserPayment(UserSession::getSupereditUser());
return $payment_service->del($paymentid);
