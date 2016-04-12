<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-04-12
 * Time: 오후 3:03
 */

namespace Intra\Service\Payment;


use Intra\Model\UserPaymentModel;
use Intra\Service\User\UserService;
use Mailgun\Mailgun;

class UserPaymentMail
{
	public static function sendMail($type, $insert_id)
	{
		$user_payment_model = new UserPaymentModel();
		$row = $user_payment_model->getPaymentWithoutUid($insert_id);
		list($title, $text, $receivers) = self::getMailContents($type, $row);
		self::sendMailRaw($receivers, $title, $text);
	}

	/**
	 * @param $type
	 * @param $row
	 * @return array
	 */
	private function getMailContents($type, $row)
	{
		$name = UserService::getNameByUidSafe($row['uid']);
		$title = "[{$type}][{$row['team']}][{$row['month']}] {$name}님의 요청, {$row['category']}";
		$text = "요청일 : {$row['request_date']}
요청자 : {$name}
분류 : {$row['team']} / {$row['product']}
내용 : {$row['category']}
상세내용 : {$row['desc']}
금액 : {$row['price']}
결제예정일 : {$row['pay_date']}";
		$receivers = [
			UserService::getEmailByUidSafe($row['uid']),
			UserService::getEmailByUidSafe($row['manager_uid'])
		];
		return [$title, $text, $receivers];
	}

	/**
	 * @param $receivers
	 * @param $title
	 * @param $text
	 */
	private function sendMailRaw($receivers, $title, $text)
	{
		$receivers[] = '***REMOVED***';
		$receivers[] = '***REMOVED***';

		$mg = new Mailgun("***REMOVED***");
		$domain = "ridibooks.com";
		$mg->sendMessage(
			$domain,
			[
				'from' => 'noreply@ridibooks.com',
				'to' => implode(', ', $receivers),
				'subject' => $title,
				'text' => $text
			]
		);
	}
}
