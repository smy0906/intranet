<?php
/** @var $this Intra\Core\Control */

use Intra\Lib\Response\CsvResponse;
use Intra\Service\UserHoliday;
use Intra\Service\UserSession;

$request = $this->getRequest();
$super_edit_user = UserSession::getSupereditUser();

if (!UserSession::getSelf()->isSuperAdmin()) {
	exit;
}

//service
{
	$user_holiday = new UserHoliday($super_edit_user);
}

//input
{
	$year = $request->get('year');
	if (!intval($year)) {
		$year = date('Y');
	}
}

//main
{
	$holidays = $user_holiday->getHolidaysAllUsers($year);
}

$csvs = array(
	'신청날짜' => 'request_date',
	'신청자' => 'uid_name',
	'결재자' => 'manager_uid_name',
	'종류' => 'type',
	'사용날짜' => 'date',
	'소모연차' => 'cost',
	'업무인수인계자' => 'keeper_uid_name',
	'비상시연락처' => 'phone_emergency',
	'비고' => 'memo',
);
$rows = array();
$rows[] = array_keys($csvs);
foreach ($holidays as $holiday) {
	$row = array();
	foreach ($csvs as $key) {
		$row[] = $holiday->$key;
	}
	$rows[] = $row;
}

$response = new CsvResponse($rows);
$response->send();
exit;
