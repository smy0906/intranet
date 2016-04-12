<?php
namespace Intra\Service\Payment;

use Intra\Model\UserPaymentModel;
use Intra\Service\User\UserDto;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

class UserPayment
{
	private $user_payment_model;
	/**
	 * @var UserDto
	 */
	private $user;

	public function __construct(UserDto $user)
	{
		$this->user = $user;

		$this->user_payment_model = new UserPaymentModel();
	}

	public function index($month, $is_type_remain_only)
	{
		$return = [];
		$return['user'] = $this->user;
		$uid = $this->user->uid;

		$prevmonth = date('Y-m', strtotime('-1 month', strtotime($month)));
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month)));

		$return['month'] = $month;
		$return['prevmonth'] = $prevmonth;
		$return['nextmonth'] = $nextmonth;
		$return['todayMonth'] = date('Y-m');
		$return['todayDate'] = date('Y-m-d');

		$return['queuedPayments'] = $this->user_payment_model->queuedPayments();
		$return['todayQueuedCount'] = $this->user_payment_model->todayQueuedCount();
		$return['todayQueuedCost'] = $this->user_payment_model->todayQueuedCost();
		$return['currentUid'] = $this->user->uid;

		if ($is_type_remain_only) {
			$payments = $return['queuedPayments'];
		} else {
			$payments = $this->user_payment_model->getPayments($uid, $month);
		}
		$return['payments'] = $this->filterPayments($payments);

		if ($this->isSuperAdmin()) {
			$return['isSuperAdmin'] = 1;
			$return['editable'] |= 1;
		}

		$return['allCurrentUsers'] = UserService::getAvailableUserDtos();
		$return['allUsers'] = UserService::getAllUserDtos();

		$return['const'] = UserPaymentConst::get();

		return $return;
	}

	/**
	 * @param $payments
	 * @return mixed
	 */
	private function filterPayments($payments)
	{
		foreach ($payments as $k => $payment) {
			$payment['manager_name'] = UserService::getNameByUidSafe($payment['manager_uid']);
			$payments[$k] = $payment;
		}
		return $payments;
	}

	public function add($request_args)
	{
		$request_args = $this->filterAdd($request_args);
		$this->assertAdd($request_args);

		$insert_id = $this->user_payment_model->add($request_args);
		if (!$insert_id) {
			throw new \Exception('자료추가 실패했습니다');
		}

		UserPaymentMail::sendMail('결제요청', $insert_id);

		return 1;
	}

	/**
	 * @param $request_args
	 * @throws \Exception
	 */
	private function assertAdd($request_args)
	{
		if (!strtotime($request_args['month'] . '-1')) {
			throw new \Exception('귀속월을 다시 입력해주세요');
		}
		if (!strtotime($request_args['pay_date'])) {
			throw new \Exception('결제(예정)일을 다시 입력해주세요');
		}
	}

	/**
	 * @param $request_args
	 * @return mixed
	 */
	private function filterAdd($request_args)
	{
		$request_args['uid'] = $this->user->uid;
		$request_args['request_date'] = date('Y-m-d');
		$request_args['month'] = preg_replace('/\D/', '/', trim($request_args['month']));
		$request_args['month'] = date('Y-m', strtotime($request_args['month'] . '/1'));
		$request_args['pay_date'] = preg_replace('/\D/', '-', trim($request_args['pay_date']));
		if (!$this->isSuperAdmin()) {
			unset($request_args['status']);
			unset($request_args['paytype']);
		}
		if (strlen($request_args['status']) == 0) {
			unset($request_args['status']);
		}
		if (strlen($request_args['paytype']) == 0) {
			unset($request_args['paytype']);
		}
		return $request_args;
	}

	public function getRow($paymentid)
	{
		if ($this->user->is_admin) {
			$payment = $this->user_payment_model->getPaymentWithoutUid($paymentid);
		} else {
			$payment = $this->user_payment_model->getPayment($paymentid, $this->user->uid);
		}
		if (!$payment) {
			throw new \Exception('invalid paymentid request');
		}
		$paymentid = $payment['paymentid'];
		return new UserPaymentRow($paymentid);
	}

	/**
	 * @return bool
	 */
	private function isSuperAdmin()
	{
		return UserSession::getSelfDto()->is_admin;
	}
}
