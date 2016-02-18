<?php

/** @var $this Intra\Core\Control */

$db = \Intra\Service\IntraDb::getGnfDb();

$user = \Intra\Service\User\UserSession::getSelf();
$request = $this->getRequest();

$uid = $user->uid;
$id = $request->get('id');
$desc = $request->get('desc');
$from = $request->get('from');
$to = $request->get('to');

if ($user->isSuperAdmin()) {
	$where = compact('id');
} else {
	$where = compact('id', 'uid');
}
$dat = compact('desc', 'from', 'to');
if ($db->sqlUpdate('room_events', $dat, $where)) {
	return 1;
}

return '예약 변경이 실패했습니다. 개발팀에 문의주세요';
