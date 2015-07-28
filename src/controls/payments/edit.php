<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserPayment;
use Intra\Service\UserSession;

$request = $this->getRequest();
$paymentid = $request->get('paymentid');
$key = $request->get('key');
$value = $request->get('value');

$payment_service = new UserPayment(UserSession::getSupereditUser());
return $payment_service->edit($paymentid, $key, $value);
