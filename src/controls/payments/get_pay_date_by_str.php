<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPayment;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$pay_type_str = $request->get('pay_type_str');

$payment_service = new UserPayment(UserSession::getSupereditUserDto());
return $payment_service->getPayDateByStr($pay_type_str);
