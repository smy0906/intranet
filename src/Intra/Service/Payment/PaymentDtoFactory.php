<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-06-02
 * Time: 오후 8:07
 */

namespace Intra\Service\Payment;


use Intra\Lib\DictsUtils;
use Intra\Model\PaymentAcceptModel;
use Intra\Model\PaymentModel;

class PaymentDtoFactory
{
	/**
	 * @param $payment_id
	 * @return PaymentDto
	 */
	public static function createFromDatabaseByPk($payment_id)
	{
		$user_payment_model = new PaymentModel();
		$payment_row = $user_payment_model->getPaymentWithoutUid($payment_id);
		return self::importFromDatabaseDicts([$payment_row])[0];
	}

	/**
	 * @param array $payment_dicts
	 * @return PaymentDto[]
	 */
	public static function importFromDatabaseDicts(array $payment_dicts)
	{
		if (count($payment_dicts) == 0) {
			return [];
		}
		$paymentids = DictsUtils::extractValuesByKey($payment_dicts, 'paymentid');
		$payment_accept_dicts = PaymentAcceptModel::getsByPaymentids($paymentids);
		$payment_accept_dicts_by_payment_id = DictsUtils::alignListByKey($payment_accept_dicts, 'paymentid');

		$return = [];
		foreach ($payment_dicts as $payment_dict) {
			$paymentid = $payment_dict['paymentid'];
			$payment_accepts_dicts = $payment_accept_dicts_by_payment_id[$paymentid];
			$payment_accepts = PaymentAcceptDtoFactory::createFromDatabaseDicts($payment_accepts_dicts);
			$return[] = PaymentDto::importFromDatabase($payment_dict, $payment_accepts);
		}
		return $return;
	}

}
