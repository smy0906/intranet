<?php
/** @var $this Intra\Core\Control */

use Intra\Service\UserReceipts;
use Intra\Service\UserSession;

$request = $this->getRequest();

$month = $request->get('month');
$day = $request->get('day');
$title = $request->get('title');
$scope = $request->get('scope');
$type = $request->get('type');
$cost = $request->get('cost');
$payment = $request->get('payment');
$note = $request->get('note');

$user = UserSession::getSupereditUser();
$payment_service = new UserReceipts($user);
return $payment_service->add($month, $day, $title, $scope, $type, $cost, $payment, $note);
