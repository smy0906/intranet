<?php

/** @var $this Intra\Core\Control */

$request = $this->getRequest();

$db = \Intra\Service\IntraDb::getGnfDb();

$user = \Intra\Service\UserSession::getSelf();

$uid = $user->uid;
$room_id = $request->get('room_id');
$desc = $request->get('desc');
$from = $request->get('from');
$to = $request->get('to');
$dat = compact('room_id', 'desc', 'from', 'to', 'uid');

return $db->sqlInsert('room_events', $dat);
