<?php
/** @var $this Intra\Core\Control */

use Intra\Model\PaymentModel;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\JsonResponse;

error_reporting(0);

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

$method = $request->getMethod();
if (strtoupper($method) === 'GET') {
	$paymentModel = new PaymentModel();
	$payment = $paymentModel->getPayments($uid, $month);
	return new JsonResponse($payment);

} else if (strtoupper($method) === 'POST') {

}
