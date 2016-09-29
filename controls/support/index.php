<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\SupportPolicy;
use Intra\Service\Support\SupportService;
use Intra\Service\User\UserConstant;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

$self = UserSession::getSelfDto();
$request = $this->getRequest();
$target = $request->get('target');
$yearmonth = $request->get('yearmonth');
$uid = $request->get('uid');
$type = $request->get('type');

if (!strlen($yearmonth)) {
	$yearmonth = date('Y-m');
}
$date = $yearmonth . '-01';
if (!intval($uid) || !UserPolicy::isSupportAdmin($self)) {
	$uid = $self->uid;
}

$prev_yearmonth = date('Y-m', strtotime('-1 month', strtotime($yearmonth)));
$next_yearmonth = date('Y-m', strtotime('+1 month', strtotime($yearmonth)));

$columns = SupportPolicy::getColumns($target);
$const = [
	'team' => UserConstant::$jeditable_key_list['team']
];
$column_dicts = SupportService::getDicts($columns, $target, $uid, $date, $type);

return [
	'uid' => $uid,
	'prev_yearmonth' => $prev_yearmonth,
	'yearmonth' => $yearmonth,
	'next_yearmonth' => $next_yearmonth,
	'target' => $target,
	'columns' => $columns,
	'column_dicts' => $column_dicts,
	'const' => $const,
	'is_admin' => UserPolicy::isSupportAdmin($self),
	'allUsers' => UserService::getAllUserDtos(),
];
