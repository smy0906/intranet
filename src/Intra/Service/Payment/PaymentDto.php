<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-04-14
 * Time: 오전 11:29
 */

namespace Intra\Service\Payment;


use Intra\Core\BaseDto;
use Intra\Core\MsgException;
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

	/**
	 * @var $manger_accept PaymentAcceptDto
	 */
	public $manger_accept;
	public $is_manager_accepted;

	/**
	 * @var $co_accept PaymentAcceptDto
	 */
	public $co_accept;
	public $is_co_accepted;

	/**
	 * @var $files FileUploadDto[]
	 */
	public $files;
	public $is_file_uploadable;


	/**
	 * @param array $payment_row []
	 * @param $payment_accepts_dtos PaymentAcceptDto[]
	 * @param $payment_files_dtos FileUploadDto[]
	 * @return PaymentDto
	 */
	public static function importFromDatabase(array $payment_row, array $payment_accepts_dtos, $payment_files_dtos)
	{
		$return = new self;
		$return->initFromArray($payment_row);
		$return->register_name = UserService::getNameByUidSafe($return->uid);
		$return->manager_name = UserService::getNameByUidSafe($return->manager_uid);

		$return->is_manager_accepted = false;
		$return->is_co_accepted = false;

		$return->files = $payment_files_dtos;
		$return->is_file_uploadable = (!in_array($return->status, ['결제 완료', '삭제']));

		foreach ($payment_accepts_dtos as $payment_accept) {
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
			throw new MsgException('승인자가 누락되었습니다. 다시 입력해주세요');
		}
		if (!$return->product) {
			throw new MsgException('프로덕트가 누락되었습니다. 다시 입력해주세요');
		}
		if (strlen($return->paytype) == 0) {
			unset($return->paytype);
		}
		if (!strtotime($return->month . '-1')) {
			throw new MsgException('귀속월을 다시 입력해주세요');
		}
		if (!strtotime($return->pay_date)) {
			throw new MsgException('결제(예정)일을 다시 입력해주세요');
		}
		return $return;
	}

	public function exportDatabaseInsert()
	{
		return $this->exportAsArrayExceptNull();
	}
}
