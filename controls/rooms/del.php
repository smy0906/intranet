<?php

/** @var $this Intra\Core\Control */

$db = \Intra\Service\IntraDb::getGnfDb();

$request = $this->getRequest();

$id = $request->get('id');
$user = \Intra\Service\User\UserSession::getSelfDto();
$uid = $user->uid;

$update = ['deleted' => 1];
if ($user->is_admin) {
	$where = compact('id');
} else {
	$where = compact('id', 'uid');
}

return $db->sqlUpdate('room_events', $update, $where);
