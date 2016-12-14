<?php

namespace Intra\Service\Cron;

use Intra\Core\MsgException;
use Intra\Service\Cron\Interfacer\CronInterface;
use Intra\Service\Cron\Job\PaymentNoticeCronMailing;
use Intra\Service\Cron\Job\PaymentRemainCronMailing;
use Intra\Service\Ridi;

class CronMaster
{
	private static $CRON_CLASSES = [
		PaymentNoticeCronMailing::class,
		PaymentRemainCronMailing::class,
	];

	public static function run()
	{
		$lock_unique_name = 'cron.master';
		if (self::tryLockAndIfFailed($lock_unique_name)) {
			die('already running');
		}

		foreach (self::$CRON_CLASSES as $cron_class) {
			try {
				/**
				 * @var $cron CronInterface
				 */
				$cron = new $cron_class;
				$cron_unique_name = $cron->getUniqueName();
				if (strlen($cron_unique_name) <= 0) {
					throw new MsgException('invalid $cron_unique_name : ' . $cron_class);
				}
				if (self::tryLockAndIfFailed($cron_unique_name)) {
					continue;
				}
				$last_executed_datetime = self::getLastExecutedDatetime($cron_unique_name);

				if (!$cron->isTimeToRun($last_executed_datetime)) {
					continue;
				}
				if ($cron->run()) {
					CronHistoryModel::logExecuted($cron_unique_name);
				}
			} catch (MsgException $e) {
				Ridi::triggerSentryException($e);
			}
		}
	}

	private static function getLastExecutedDatetime($cron_class_name)
	{
		$datetime = CronHistoryModel::getLastTime($cron_class_name);
		if ($datetime) {
			return new \DateTime($datetime);
		}

		return new \DateTime('1000-00-00 00:00:00');
	}

	/**
	 * @param $lock_unique_name
	 *
	 * @return bool
	 */
	private static function tryLockAndIfFailed($lock_unique_name)
	{
		$lock = fopen(sys_get_temp_dir() . '/ridi.intranet.' . $lock_unique_name . '.lock', 'c+');
		if (!flock($lock, LOCK_EX | LOCK_NB)) {
			return true;
		}

		return false;
	}
}
