<?php

namespace Intra\Service\Cron;

use Intra\Lib\ObjectsUtils;
use Intra\Model\PaymentModel;
use Intra\Service\Payment\PaymentDtoFactory;
use Intra\Service\User\UserService;

class PaymentCron extends CronInterface
{
	/**
	 * @param $last_executed_datetime \DateTime
	 * @return bool
	 */
	public function isOnTimeToRun($last_executed_datetime)
	{
		$last_date = $last_executed_datetime->format('Y/m/d');
		$today_date = date('Y/m/d');
		if ($last_date == $today_date) {
			return false;
		}
		$hour = date('H');
		if ($hour < 10) {
			return false;
		}
		return true;
	}

	/**
	 * @return MailingDto[]
	 */
	public function getMailContentsDicts()
	{
		$dto_template = new MailingDto;
		$dto_template->replyTo = ['***REMOVED***'];
		$dto_template->title = '[결제요청] 결제 3일전 리마인드 메일 (' . date('Y-m-d') . ')';

		$return_dtos = [];
		$dicts = PaymentModel::getRemindMailBefore3days();
		$payments = PaymentDtoFactory::importFromDatabaseDicts($dicts);
		$payments_by_uid = ObjectsUtils::alignListByKey($payments, 'uid');

		foreach ($payments_by_uid as $uid => $payments) {
			$first_payment = $payments[0];
			$dto = clone $dto_template;
			$dto->receiver = [
				UserService::getEmailByUidSafe($first_payment->manager_uid),
				UserService::getEmailByUidSafe($first_payment->uid),
				'***REMOVED***',
			];
			$dto->dicts = [];
			foreach ($payments as $payment) {
				$dto->dicts[] =
					[
						'요청일' => $payment->request_date,
						'요청자' => $payment->register_name,
						'결제 예정일' => $payment->pay_date,
						'귀속부서' => $payment->team,
						'프로덕트' => $payment->product,
						'분류' => $payment->category,
						'상세내역' => $payment->desc,
						'비고' => $payment->note,
						'금액' => number_format($payment->price),
						'결제수단' => $payment->paytype,
						'상태' => $payment->status,
					];
			}
			$return_dtos[] = $dto;
		}

		return $return_dtos;
	}
}
