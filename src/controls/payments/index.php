<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserPayment;
use Intra\Service\UserSession;

$request = $this->getRequest();
$month = $request->get('month');

$month = UserPayment::parseMonth($month);

$payment_service = new UserPayment(UserSession::getSupereditUser());
return $payment_service->index($month);
