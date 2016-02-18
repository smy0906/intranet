<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Holiday\UserHolidayDto;
use Intra\Service\Holiday\UserHoliday;
use Intra\Service\IntraDb;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$super_edit_user = UserSession::getSupereditUser();

//service
{
	$user_holiday = new UserHoliday($super_edit_user);
}

//input
{
	$holiday_raw = new UserHolidayDto;
	$holiday_raw->date = $request->get('date');
	$holiday_raw->keeper_uid = $request->get('keeper_uid');
	$holiday_raw->manager_uid = $request->get('manager_uid');
	$holiday_raw->memo = $request->get('memo');
	$holiday_raw->phone_emergency = $request->get('phone_emergency');
	$holiday_raw->type = $request->get('type');
	$holiday_raw->cost = $request->get('cost');
}

//filter
{
	$holiday_raw->date = date('Y-m-d', strtotime($holiday_raw->date));
	$holiday_raw->phone_emergency = trim($holiday_raw->phone_emergency);
	$holiday_raw->yearly = $user_holiday->getYearly(strtotime($holiday_raw->date));
}

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
