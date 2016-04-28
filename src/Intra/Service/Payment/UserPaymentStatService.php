<?php
namespace Intra\Service\Payment;

use Intra\Lib\Response\CsvResponse;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

class UserPaymentStatService
{
	/**
	 * @param $payments PaymentDto[]
	 * @return string
	 */
	public function sendExcelResposeAndExit($payments)
	{
		if (!UserSession::getSelfDto()->is_admin) {
			return '권한이 없습니다';
		}
		//header
		$csvs = [];
		$arr = [
			'요청일',
			'요청자',
			'승인자',
			'귀속월',
			'귀속부서',
			'프로덕트',
			'분류',
			'상세내역',
			'업체명',
			'입금은행',
			'입금계좌번호',
			'예금주',
			'입금금액',
			'결제예정일',
			'세금계산서수취여부',
			'비고',
			'결제수단',
			'상태'
		];
		$csvs[] = $arr;
		foreach ($payments as $payment) {
			$arr = [
				$payment->request_date,
				UserService::getNameByUidSafe($payment->uid),
				UserService::getNameByUidSafe($payment->manager_uid),
				$payment->month,
				$payment->team,
				$payment->product,
				$payment->category,
				$payment->desc,
				$payment->company_name,
				$payment->bank,
				'"' . $payment->bank_account . '"',
				$payment->bank_account_owner,
				$payment->price,
				$payment->pay_date,
				$payment->tax,
				$payment->note,
				$payment->paytype,
				$payment->status,
			];
			$csvs[] = $arr;
		}

		$csvresponse = new CsvResponse($csvs);
		$csvresponse->send();
		exit;
	}
}
