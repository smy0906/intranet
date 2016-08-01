<?php
namespace Intra\Service\Payment;

use Intra\Config\Config;
use Intra\Core\Application;
use Intra\Service\User\UserService;
use Mailgun\Mailgun;

class UserPaymentMailService
{
	public static function sendMail($type, $payment_id)
	{
		$payment_dto = PaymentDtoFactory::createFromDatabaseByPk($payment_id);
		list($title, $html, $receivers) = self::getMailContents($type, $payment_dto);
		self::sendMailRaw($receivers, $title, $html);
	}

	/**
	 * @param $type
	 * @param $row
	 * @return array
	 */
	private function getMailContents($type, PaymentDto $row)
	{
		$title = "[{$type}][{$row->team}][{$row->month}] {$row->register_name}님의 요청, {$row->category}";
		$html = Application::$view->render('payments/template/add', ['item' => $row]);
		$receivers = [
			UserService::getEmailByUidSafe($row->uid),
			UserService::getEmailByUidSafe($row->manager_uid)
		];
		return [$title, $html, $receivers];
	}

	/**
	 * @param $receivers
	 * @param $title
	 * @param $html
	 */
	private function sendMailRaw($receivers, $title, $html)
	{
		$receivers = array_merge($receivers, Config::$recipients['payment']);

		if (Config::$is_dev) {
			if (strlen(Config::$test_mail)) {
				$receivers = [Config::$test_mail];
			} else {
				return;
			}
		}

		$mg = new Mailgun("***REMOVED***");
		$domain = "ridibooks.com";
		$mg->sendMessage(
			$domain,
			[
				'from' => 'noreply@ridibooks.com',
				'to' => implode(', ', $receivers),
				'subject' => $title,
				'html' => $html
			]
		);
	}
}
