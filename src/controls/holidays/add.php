<?php
/** @var $this Intra\Core\Control */

use Intra\Model\HolidayRaw;
use Intra\Service\IntraDb;
use Intra\Service\UserHoliday;
use Intra\Service\UserSession;

$request = $this->getRequest();
$super_edit_user = UserSession::getSupereditUser();

//service
{
	$user_holiday = new UserHoliday($super_edit_user);
}

//input
{
	$holiday_raw = new HolidayRaw;
	$holiday_raw->yearly = $user_holiday->getYearly();
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
}

//finalize
{
	$db = IntraDb::getGnfDb();
	$db->sqlBegin();
	if ($holidayid = $user_holiday->add($holiday_raw)) {
		if ($user_holiday->sendNotification($holidayid, "휴가신청")) {
			if ($db->sqlEnd()) {
				return 1;
			}
		}
	}
}

return 0;
