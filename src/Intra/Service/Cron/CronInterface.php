<?php

namespace Intra\Service\Cron;

abstract class CronInterface
{
	/**
	 * @param $last_executed_datetime \DateTime
	 * @return bool
	 */
	abstract public function isOnTimeToRun($last_executed_datetime);

	/**
	 * @return MailingDto[]
	 */
	abstract public function getMailContentsDicts();
}
