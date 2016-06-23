<?php

namespace Intra\Service\Cron\Interfacer;

use Intra\Config\Config;
use Intra\Core\MsgException;
use Intra\Lib\DictsUtils;
use Intra\Service\Cron\MailingDto;
use Mailgun\Mailgun;

abstract class CronMailingInterface extends CronInterface
{
	/**
	 * @return MailingDto[]
	 */
	abstract public function getMailContentsDtos();

	/**
	 * @param $last_executed_datetime \DateTime
	 * @return bool
	 */
	public function isToday($last_executed_datetime)
	{
		$last_date = $last_executed_datetime->format('Y/m/d');
		$today_date = date('Y/m/d');
		if ($last_date == $today_date) {
			return true;
		}
		return false;
	}

	public function reformatDatetime($format, $request_date)
	{
		$datetime = new \DateTime($request_date);
		return $datetime->format($format);
	}

	/**
	 * @return bool
	 * @throws MsgException
	 */
	public function run()
	{
		$dtos = $this->getMailContentsDtos();
		if (!is_array($dtos)) {
			throw new MsgException('invalid getMailContentsDtos : ' . get_called_class());
		}
		foreach ($dtos as $dto) {
			self::send($dto);
		}
		return true;
	}

	/**
	 * @param $dto MailingDto
	 * @throws MsgException
	 * @throws \Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters
	 */
	private static function send($dto)
	{
		$mg = new Mailgun("***REMOVED***");
		$domain = "ridibooks.com";

		$html = $dto->body_header . DictsUtils::convertDictsToHtmlTable($dto->dicts) . $dto->body_footer;
		$mail_post = [
			'from' => 'noreply@ridibooks.com',
			'subject' => $dto->title,
			'html' => $html
		];

		$dto->receiver = self::filterMails($dto->receiver);
		$dto->replyTo = self::filterMails($dto->replyTo);
		$dto->CC = self::filterMails($dto->CC);
		$dto->BCC = self::filterMails($dto->BCC);

		if (!$dto->receiver) {
			throw new MsgException('empty mail receiver');
		}

		if ($dto->receiver) {
			$mail_post['to'] = $dto->receiver;
			if (strlen(Config::$test_mail)) {
				$mail_post['to'] = Config::$test_mail;
			}
		}
		if ($dto->replyTo) {
			$mail_post['h:Reply-To'] = $dto->replyTo;
		}
		if ($dto->CC) {
			$mail_post['cc'] = $dto->CC;
		}
		if ($dto->BCC) {
			$mail_post['bcc'] = $dto->BCC;
		}

		$mg->sendMessage(
			$domain,
			$mail_post
		);
	}

	/**
	 * @param $mailReceiver mixed
	 * @return \string[]
	 * @throws \Exception
	 */
	private function filterMails($mailReceiver)
	{
		if (is_null($mailReceiver)) {
			return $mailReceiver;
		}
		if (!is_array($mailReceiver)) {
			throw new MsgException('unexpeced mail list : ' . strval($mailReceiver));
		}
		$mailReceiver = array_unique($mailReceiver);
		$mailReceiver = array_filter($mailReceiver);
		return implode(',', $mailReceiver);
	}
}
