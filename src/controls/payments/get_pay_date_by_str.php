<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPaymentRequestFilter;
use Intra\Service\Payment\UserPayment;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$pay_type_str = $request->get('pay_type_str');

return UserPaymentRequestFilter::getPayDateByStr($pay_type_str);
