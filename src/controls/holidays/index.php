<?php
/** @var $this Intra\Core\Control */

use Intra\Model\UserFactory;
use Intra\Model\UserHolidayModel;
use Intra\Service\UserHoliday;
use Intra\Service\UserHolidayPolicy;
use Intra\Service\UserSession;

$request = $this->getRequest();
$self = UserSession::getSelf();
$super_edit_user = UserSession::getSupereditUser();

if ($self->isSuperAdmin()) {
	$editable = 1;
}

//service
{
	$user_holiday = new UserHoliday($super_edit_user);
	$user_holiday_policy = new UserHolidayPolicy($super_edit_user);
}

//input
$year = $request->get('year');
if (!intval($year)) {
	$year = date('Y');
}

$joinYear = $user_holiday->getYearByYearly(0);
$yearly = $year - $joinYear;

//main
{
	$today = date('Y-m-d');
	$holidayConst = UserHolidayModel::$const;
	$yearPrev = $year - 1;
	$yearNext = $year + 1;
	$yearlyFrom = date('Y-m-d', $user_holiday_policy->getYearlyBeginTimestamp($yearly));
	$yearlyTo = date('Y-m-d', $user_holiday_policy->getYearlyEndTimestamp($yearly));

	$fullCost = $user_holiday_policy->getAvailableCost($yearly);
	$usedCost = $user_holiday_policy->getUsedCost($yearly);
	$remainCost = $user_holiday_policy->getRemainCost($yearly);
	$holidays = $user_holiday->getUserHolidays($yearly);
	$holidayInfo = $user_holiday_policy->getDetailInfomationByYearly($yearly);

	$availableUsers = UserFactory::getAvailableUsers();
	$managerUsers = UserFactory::getManagerUsers();
}

return compact(
	'today',
	'month',
	'prevMonth',
	'nextMonth',
	'holidays',
	'year',
	'yearly',
	'yearPrev',
	'yearNext',
	'yearlyFrom',
	'yearlyTo',
	'fullCost',
	'remainCost',
	'editable',
	'self',
	'super_edit_user',
	'availableUsers',
	'holidays',
	'holidayConst',
	'holidayInfo',
	'managerUsers'
);
