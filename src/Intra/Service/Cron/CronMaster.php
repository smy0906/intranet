<?php

namespace Intra\Service\Cron;

use Intra\Core\MsgException;
use Intra\Lib\DictsUtils;
use Intra\Service\Ridi;
use Mailgun\Mailgun;

class CronMaster
{
	private static $CRON_CLASSES = [PaymentCron::class];

	public static function run()
	{
		foreach (self::$CRON_CLASSES as $cron_class) {
			try {
				/**
				 * @var $cron CronInterface
				 */
				$cron = new $cron_class;
				$cron_class_name = strval($cron_class);
				$last_executed_datetime = self::getLastExecutedDatetime($cron_class_name);

				if (!$cron->isOnTimeToRun($last_executed_datetime)) {
					continue;
				}
				$dtos = $cron->getMailContentsDicts();
				if (!is_array($dtos)) {
					throw new MsgException('invalid getMailContentsDicts : ' . $cron_class_name);
				}
				foreach ($dtos as $dto) {
					self::send($dto);
				}
				CronHistoryModel::logExecuted($cron_class_name);
			} catch (MsgException $e) {
				Ridi::triggerSentryException($e);
			}
		}
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
		$mail_post = [
			'from' => 'noreply@ridibooks.com',
			'subject' => $dto->title,
			'html' => DictsUtils::convertDictsToHtmlTable($dto->dicts)
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
			$mail_post['to'] = '***REMOVED***';
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

	private function getLastExecutedDatetime($cron_class_name)
	{
		$datetime = CronHistoryModel::getLastTime($cron_class_name);
		if ($datetime) {
			return new \DateTime($datetime);
		}
		return new \DateTime('1000-00-00 00:00:00');
	}
}
