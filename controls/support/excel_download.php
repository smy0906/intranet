<?php
/** @var $this Intra\Core\Control */

use Intra\Lib\Response\CsvResponse;
use Intra\Service\Support\SupportPolicy;
use Intra\Service\Support\SupportViewDtoFactory;
use Intra\Service\User\UserConstant;
use Intra\Service\User\UserDtoFactory;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$target = $request->get('target');
$type = $request->get('type');
$yearmonth = $request->get('yearmonth');
if ($type == 'year') {
	$date = date_create($yearmonth . '-01');
	$begin_datetime = (clone $date)->modify("first day of this year");
	$end_datetime = (clone $begin_datetime)->modify("first day of next year");
} elseif ($type == 'yearmonth') {
	$begin_datetime = date_create($yearmonth . '-01');
	$end_datetime = (clone $begin_datetime)->modify("first day of this month next month");
} else {
	throw new \Intra\Core\MsgException('invalid type');
}

$columns = SupportPolicy::getColumnFieldsTestUserDto($target, $self);
$const = [
	'teams' => UserConstant::$jeditable_key_list['team'],
	'managers' => UserDtoFactory::createManagerUserDtos(),
	'users' => UserDtoFactory::createAvailableUserDtos(),
];
$support_view_dtos = SupportViewDtoFactory::getsForExcel($columns, $target, $begin_datetime, $end_datetime);

$csvs = [];
$csv_header = [];
foreach ($columns as $column_name => $column) {
	$csv_header[] = $column_name;
}
$csvs[] = $csv_header;

foreach ($support_view_dtos as $support_view_dto) {
	$csv_row = [];
	foreach ($columns as $column_name => $column) {
		$csv_row[] = $support_view_dto->display_dict[$column->key];
	}
	$csvs[] = $csv_row;
}

return CsvResponse::create($csvs);
