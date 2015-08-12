<?php
/** @var $this Intra\Core\Control */

use Intra\Lib\Response\CsvResponse;
use Intra\Service\UserHoliday;
use Intra\Service\UserHolidayPolicy;
use Intra\Service\Users;
use Intra\Service\UserSession;

$request = $this->getRequest();
$users_service = new Users();
$self = UserSession::getSelf();

if (!$self->isSuperAdmin()) {
	exit;
}

$users = $users_service->getAllUsers();


$year = $request->get('year');
if (!intval($year)) {
	$year = date('Y');
}

$rows = array(
	array('연도', '이름', '입사일자', '퇴사일자', '연차부여', '사용일수', '잔여일수')
);

foreach ($users as $user) {
	$user_holiday = new UserHoliday($user);
	$user_holiday_policy = new UserHolidayPolicy($user);

	$joinYear = $user_holiday->getYearByYearly(1);
	$yearly = $year - $joinYear + 1;

	$fullCost = $user_holiday_policy->getAvailableCost($yearly);
	$usedCost = $user_holiday_policy->getUsedCost($yearly);
	$remainCost = $fullCost - $usedCost;

	$user_row = $user->getDbDto();

	$rows[] = array(
		$year,
		$user->getName(),
		$user_row['on_date'],
		$user_row['off_date'],
		$fullCost,
		$usedCost,
		$remainCost
	);
}

$response = new CsvResponse($rows);
$response->send();
exit;
