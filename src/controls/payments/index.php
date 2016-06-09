<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPaymentRequestFilter;
use Intra\Service\Payment\UserPaymentService;
use Intra\Service\User\UserInstanceService;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$uid = $request->get('uid');
if (!intval($uid) || !UserPolicy::isPaymentAdmin($self)) {
	$uid = $self->uid;
}
$month = $request->get('month');
if (!strlen($month)) {
	$month = date('Y-m');
}
$is_type_remain_only = ($request->get('type') == 'remain');

$month = UserPaymentRequestFilter::parseMonth($month);

$user_dto_object = UserInstanceService::importFromDatabaseWithUid($uid);
$target_user_dto = $user_dto_object->exportDto();

$payment_service = new UserPaymentService($target_user_dto);
return $payment_service->index($month, $is_type_remain_only);
