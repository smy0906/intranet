<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-04-14
 * Time: 오전 11:29
 */

namespace Intra\Service\Payment;


use Intra\Core\BaseDto;

class PaymentAcceptDto extends BaseDto
{
	public $paymentid;
	public $uid;
	public $user_type;

	/**
	 * html view only
	 */
	public $created_datetime;

	public static function importFromDatabaseRow(array $payment_accept_row)
	{
		$return = new self;
		$return->initFromArray($payment_accept_row);
		return $return;
	}

	public static function importFromAddRequest($paymentid, $uid, $user_type)
	{
		$return = new self;
		$return->paymentid = $paymentid;
		$return->uid = $uid;
		$return->user_type = $user_type;

		return $return;
	}

	public function exportDatabaseInsert()
	{
		return $this->exportAsArrayExceptNull();
	}
}
