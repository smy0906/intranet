<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\PaymentDto;
use Intra\Service\Payment\UserPaymentService;
use Intra\Service\User\UserInstanceService;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$uid = $request->get('uid');
if (!intval($uid)) {
	$uid = $self->uid;
}

$payment_dto = PaymentDto::importFromAddRequest($request, $uid, $self->is_admin);

$user_dto_object = UserInstanceService::importFromDatabaseWithUid($uid);
$target_user_dto = $user_dto_object->exportDto();

$payment_service = new UserPaymentService($target_user_dto);
return $payment_service->add($payment_dto);
