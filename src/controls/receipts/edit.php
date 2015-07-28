<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserReceipts;
use Intra\Service\UserSession;

$request = $this->getRequest();
$receiptid = $request->get('receiptid');
$key = $request->get('key');
$value = $request->get('value');

$user = UserSession::getSupereditUser();
$payment_service = new UserReceipts($user);
return $payment_service->edit($receiptid, $key, $value);
