<?php
/** @var $this Intra\Core\Control */


$db = \Intra\Service\IntraDb::getGnfDb();

$rooms = $db->sqlDicts('select * from rooms');
$name = \Intra\Service\UserSession::getSelf()->getName();

return compact('rooms', 'name');
