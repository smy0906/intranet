<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserPayment;
use Intra\Service\UserSession;

$request = $this->getRequest();
$key = $request->get('key');

$payment_service = new UserPayment(UserSession::getSupereditUser());
return $payment_service->getConstValueByKey($key);
