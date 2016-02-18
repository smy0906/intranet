<?php
/** @var $this Intra\Core\Control */


$db = \Intra\Service\IntraDb::getGnfDb();

$rooms = $db->sqlDicts('select * from rooms');
$name = \Intra\Service\User\UserSession::getSelf()->getName();

return compact('rooms', 'name');
