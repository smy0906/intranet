<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-04-12
 * Time: 오후 4:25
 */

namespace Intra\Service\Payment;


use Intra\Model\UserPaymentModel;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

class UserPaymentRow
{
	private $user_payment_model;
	private $payment_id;

	public function __construct($payment_id)
	{
		$this->user_payment_model = new UserPaymentModel();
		$this->payment_id = $payment_id;
	}

	public function edit($key, $new_value)
	{
		$old_value = $this->user_payment_model->getValueByKey($this->payment_id, $key);
		if (!$this->assertEdit($key, $old_value, $new_value)) {
			return $old_value;
		}

		$this->user_payment_model->update($this->payment_id, $key, $new_value);
		$updated_value = $this->user_payment_model->getValueByKey($this->payment_id, $key);

		if ($key == 'status') {
			if ($updated_value == '결제 완료') {
				$type = '결제완료';
				UserPaymentMail::sendMail($type, $this->payment_id);
			}
		} elseif ($key == 'price') {
			return number_format($updated_value) . ' 원';
		} elseif ($key == 'manager_uid') {
			$user_name = UserService::getNameByUidSafe($updated_value);
			if ($user_name === null) {
				return 'error';
			}
			return $user_name;
		}
		return $updated_value;
	}

	private function assertEdit($key, $old_value, $new_value)
	{
		if ($key == 'date') {
			//날짜를 변경할때 다른 월로는 변경불가
			$month_new = date('Ym', strtotime($new_value));
			$month_old = date('Ym', strtotime($old_value));
			if ($month_new != $month_old) {
				return false;
			}
		}

		if (!UserSession::getSelfDto()->is_admin) {
			return false;
		}
		return true;
	}

	public function del()
	{
		$res = $this->user_payment_model->del($this->payment_id);
		if ($res) {
			return 1;
		}
		return '삭제가 실패했습니다!';
	}
}
