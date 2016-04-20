<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-04-14
 * Time: 오전 11:29
 */

namespace Intra\Service\Payment;


use Intra\Core\BaseDto;
use Intra\Model\PaymentAcceptModel;
use Intra\Service\User\UserService;
use Symfony\Component\HttpFoundation\Request;

class PaymentDto extends BaseDto
{
	public $paymentid;
	public $uid;
	public $manager_uid;
	public $request_date;
	public $month;
	public $team;
	public $product;
	public $category;
	public $desc;
	public $company_name;
	public $bank;
	public $bank_account;
	public $bank_account_owner;
	public $price;
	public $pay_date;
	public $tax;
	public $note;
	public $paytype;
	public $status;


	/**
	 * html view only
	 */
	public $register_name;
	public $manager_name;

	public $is_manager_accepted;
	public $manger_accept;

	public $is_co_accepted;
	public $co_accept;


	/**
	 * @param $payment_row []
	 * @param $payment_accept_rows PaymentAcceptDto[]
	 * @return PaymentDto
	 */
	private static function implementsImportFromDatabaseRow(array $payment_row, array $payment_accept_rows)
	{
		$return = new self;
		$return->initFromArray($payment_row);
		$return->register_name = UserService::getNameByUidSafe($return->uid);
		$return->manager_name = UserService::getNameByUidSafe($return->manager_uid);

		$return->is_manager_accepted = false;
		$return->is_co_accepted = false;

		foreach ($payment_accept_rows as $payment_accept) {
			if ($payment_accept->paymentid == $return->paymentid) {
				if ($payment_accept->user_type == 'manager') {
					$return->manger_accept = $payment_accept;
					$return->is_manager_accepted = true;
				}
				if ($payment_accept->user_type == 'co') {
					$return->co_accept = $payment_accept;
					$return->is_co_accepted = true;
				}
			}
		}
		return $return;
	}

	/**
	 * @param array $payment_row
	 * @return PaymentDto
	 */
	public static function importFromDatabaseRow($payment_row)
	{
		return self::importFromDatabaseRowMap([$payment_row])[0];
	}

	/**
	 * @param array $payment_rows
	 * @return PaymentDto[]
	 */
	public static function importFromDatabaseRowMap(array $payment_rows)
	{
		if (count($payment_rows) == 0) {
			return [];
		}
		$paymentids = [];
		foreach ($payment_rows as $payment_row) {
			$paymentids[] = $payment_row['paymentid'];
		}
		$payment_accept_rows = PaymentAcceptModel::getsByPaymentids($paymentids);

		$payment_accept_by_payment_id = [];
		foreach ($payment_accept_rows as $payment_accept_row) {
			$payment_accept = PaymentAcceptDto::importFromDatabaseRow($payment_accept_row);
			$payment_accept_by_payment_id[$payment_accept->paymentid][] = $payment_accept;
		}

		$return = [];
		foreach ($payment_rows as $payment_row) {
			$paymentid = $payment_row['paymentid'];
			$payment_accepts = $payment_accept_by_payment_id[$paymentid];
			if (!is_array($payment_accepts)) {
				$payment_accepts = [];
			}
			$return[] = self::implementsImportFromDatabaseRow($payment_row, $payment_accepts);
		}
		return $return;
	}

	public static function importFromAddRequest(Request $request, $uid, $is_admin)
	{
		$return = new self;
		$keys = [
			'month',
			'manager_uid',
			'team',
			'product',
			'category',
			'desc',
			'company_name',
			'price',
			'bank',
			'bank_account',
			'bank_account_owner',
			'pay_date',
			'tax',
			'note',
			'paytype',
			'status',
		];
		foreach ($keys as $key) {
			$return->$key = $request->get($key);
		}

		$return->uid = $uid;
		if (!$is_admin) {
			unset($return->status);
			unset($return->paytype);
		}

		$return->request_date = date('Y-m-d');
		$return->month = preg_replace('/\D/', '/', trim($return->month));
		$return->month = date('Y-m', strtotime($return->month . '/1'));
		$return->pay_date = preg_replace('/\D/', '-', trim($return->pay_date));
		if (strlen($return->status) == 0) {
			unset($return->status);
		}
		if (!$return->manager_uid) {
			throw new \Exception('승인자가 누락되었습니다. 다시 입력해주세요');
		}
		if (strlen($return->paytype) == 0) {
			unset($return->paytype);
		}
		if (!strtotime($return->month . '-1')) {
			throw new \Exception('귀속월을 다시 입력해주세요');
		}
		if (!strtotime($return->pay_date)) {
			throw new \Exception('결제(예정)일을 다시 입력해주세요');
		}
		return $return;
	}

	public function exportDatabaseInsert()
	{
		return $this->exportAsArrayExceptNull();
	}
}
