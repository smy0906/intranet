<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPayment;
use Intra\Service\User\UserInstanceService;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$request_args = [
	'month' => $request->get('month'),
	'manager_uid' => $request->get('manager_uid'),
	'team' => $request->get('team'),
	'product' => $request->get('product'),
	'category' => $request->get('category'),
	'desc' => $request->get('desc'),
	'company_name' => $request->get('company_name'),
	'price' => $request->get('price'),
	'bank' => $request->get('bank'),
	'bank_account' => $request->get('bank_account'),
	'bank_account_owner' => $request->get('bank_account_owner'),
	'pay_date' => $request->get('pay_date'),
	'tax' => $request->get('tax'),
	'note' => $request->get('note'),
	'paytype' => $request->get('paytype'),
	'status' => $request->get('status'),
];

$uid = $request->get('uid');
if (!intval($uid)) {
	$uid = $self->uid;
}

$user_dto_object = UserInstanceService::importFromDatabaseWithUid($uid);
$target_user_dto = $user_dto_object->exportDto();

$payment_service = new UserPayment($target_user_dto);
return $payment_service->add($request_args);
