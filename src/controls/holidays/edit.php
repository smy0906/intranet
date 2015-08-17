<?php
/** @var $this Intra\Core\Control */

use Intra\Service\IntraDb;
use Intra\Service\UserHoliday;
use Intra\Service\UserSession;

$request = $this->getRequest();

$super_edit_user = UserSession::getSupereditUser();

$uid = $super_edit_user->uid;

$holidayid = $request->get('holidayid');
$key = $request->get('key');
$value = $request->get('value');

$user_holiday = new UserHoliday($super_edit_user);

$db = IntraDb::getGnfDb();
$db->sqlBegin();
$ret = $user_holiday->edit($holidayid, $key, $value);
if ($user_holiday->sendNotification(array($holidayid), "휴가수정")) {
	if ($db->sqlEnd()) {
		return $ret;
	}
}

return 'error';
