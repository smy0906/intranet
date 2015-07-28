<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserPayment;
use Intra\Service\UserSession;

$request = $this->getRequest();
$pay_type_str = $request->get('pay_type_str');

$payment_service = new UserPayment(UserSession::getSupereditUser());
return $payment_service->get_pay_date_by_str($pay_type_str);
