<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Holiday\UserHoliday;
use Intra\Service\Holiday\UserHolidayDto;
use Intra\Service\IntraDb;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$super_edit_user = UserSession::getSupereditUserDto();

$user_holiday = new UserHoliday($super_edit_user);
$holiday_raw = UserHolidayDto::importAddRequest($request, $user_holiday->getYearly(strtotime($holiday_raw->date)));

//finalize
$db = IntraDb::getGnfDb();
$db->sqlBegin();
if ($holiday_ids = $user_holiday->add($holiday_raw)) {
	if ($user_holiday->sendNotification($holiday_ids, "휴가신청")) {
		if ($db->sqlEnd()) {
			return 1;
		}
	}
}

return 0;
