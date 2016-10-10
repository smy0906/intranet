<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\SupportPolicy;
use Intra\Service\Support\SupportViewDtoFactory;
use Intra\Service\User\UserConstant;
use Intra\Service\User\UserDtoFactory;
use Intra\Service\User\UserPolicy;
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
	'teams' => UserConstant::$jeditable_key_list['team'],
	'managers' => UserDtoFactory::createManagerUserDtos(),
	'users' => UserDtoFactory::createAvailableUserDtos(),
];
$support_view_dtos = SupportViewDtoFactory::gets($columns, $target, $uid, $date, $type);

return [
	'uid' => $uid,
	'prev_yearmonth' => $prev_yearmonth,
	'yearmonth' => $yearmonth,
	'next_yearmonth' => $next_yearmonth,
	'target' => $target,
	'columns' => $columns,
	'support_view_dtos' => $support_view_dtos,
	'const' => $const,
	'is_admin' => UserPolicy::isSupportAdmin($self),
	'all_users' => UserDtoFactory::createAllUserDtos(),
];
