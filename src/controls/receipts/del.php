<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Receipt\UserReceipts;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$receiptid = $request->get('receiptid');

$user = UserSession::getSupereditUser();
$payment_service = new UserReceipts($user);
return $payment_service->del($receiptid);
