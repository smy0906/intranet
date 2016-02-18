<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Receipt\UserReceipts;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$month = $request->get('month');

$month = UserReceipts::parseMonth($month);
$user = UserSession::getSupereditUser();
$payment_service = new UserReceipts($user);
return $payment_service->index($month);
