<?php
/** @var $this Intra\Core\Control */

use Intra\Service\IntraDb;
use Intra\Service\User\UserSession;

$db = IntraDb::getGnfDb();

$request = $this->getRequest();
$type = $request->get('type');

if (!strlen($type)) {
	$type = 'default';
}

$where = [
	'is_visible' => 1,
	'type' => $type,
];

$rooms = $db->sqlDicts('select * from rooms  where ?', sqlWhere($where));
$name = UserSession::getSelfDto()->name;

return compact('rooms', 'name');
