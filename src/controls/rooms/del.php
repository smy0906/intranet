<?php

/** @var $this Intra\Core\Control */

$db = \Intra\Service\IntraDb::getGnfDb();

$request = $this->getRequest();

$id = $request->get('id');
$user = \Intra\Service\UserSession::getSelf();
$uid = $user->uid;

$update = array('deleted' => 1);
if ($user->isSuperAdmin()) {
	$where = compact('id');
} else {
	$where = compact('id', 'uid');
}

return $db->sqlUpdate('room_events', $update, $where);
