<?php
/** @var $this Intra\Core\Control */

use Intra\Model\PaymentModel;
use Intra\Service\Payment\PaymentDto;
use Intra\Service\Payment\PaymentDtoFactory;
use Intra\Service\Payment\UserPaymentRequestFilter;
use Intra\Service\Payment\UserPaymentStatService;
use Intra\Service\User\UserSession;

if (!UserSession::getSelfDto()->is_admin) {
	exit;
}

/**
 * @var $payments PaymentDto[]
 */

$request = $this->getRequest();
$month = $request->get('month');
$month = UserPaymentRequestFilter::parseMonth($month);
$month = date('Y/m/1', strtotime($month));

$payment_service = new UserPaymentStatService();
$user_payment_model = new PaymentModel();
$payments = PaymentDtoFactory::importFromDatabaseDicts($user_payment_model->queuedPayments());
$payment_service->sendExcelResposeAndExit($payments);
