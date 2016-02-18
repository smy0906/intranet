<?php

/** @var $this Intra\Core\Control */

use Intra\Service\Holiday\UserHoliday;
use Intra\Service\IntraDb;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$super_edit_user = UserSession::getSupereditUserDto();

//service
{
	$user_holiday = new UserHoliday($super_edit_user);
}

//input
{
	$holidayid = $request->get('holidayid');
}

//finalize
$db = IntraDb::getGnfDb();
$db->sqlBegin();
if ($user_holiday->del($holidayid)) {
	if ($user_holiday->sendNotification([$holidayid], '휴가취소')) {
		if ($db->sqlEnd()) {
			return 1;
		}
	}
}

return 0;
