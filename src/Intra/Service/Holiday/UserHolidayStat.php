<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-27
 * Time: 오후 12:16
 */

namespace Intra\Service\Holiday;

use Intra\Model\UserHolidayModel;

class UserHolidayStat
{
	public function __construct()
	{
		$this->user_holiday_model = new UserHolidayModel();
	}

	public function getHolidaysAllUsers($year)
	{
		$begin = date($year . '/1/1');
		$end = date(($year) . '/12/31');
		$holidays = $this->user_holiday_model->getHolidaysByUserYearly(null, $begin, $end);

		UserHoliday::filterHolidays($holidays);

		return $holidays;
	}
}
