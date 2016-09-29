<?php

/** @var $this Intra\Core\Control */

$request = $this->getRequest();

$db = \Intra\Service\IntraDb::getGnfDb();

$user = \Intra\Service\User\UserSession::getSelfDto();

$uid = $user->uid;
$room_id = $request->get('room_id');
$desc = $request->get('desc');
$from = $request->get('from');
$to = $request->get('to');
$dat = compact('room_id', 'desc', 'from', 'to', 'uid');

$db->sqlInsert('room_events', $dat);
return $db->insert_id();
