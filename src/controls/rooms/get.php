<?php

/** @var $this Intra\Core\Control */

$db = \Intra\Service\IntraDb::getGnfDb();

$request = $this->getRequest();
$from = $request->get('from');
$to = $request->get('to');

$where = [
	'deleted' => 0,
	'from' => sqlGreaterEqual($from),
	'to' => sqlLesser($to)
];

$events = $db->sqlDicts('select * from room_events where ?', sqlWhere($where));
$datas = [];
foreach ($events as $event) {
	$datas[] = [
		'id' => $event['id'],
		'start_date' => $event['from'],
		'end_date' => $event['to'],
		'text' => $event['desc'],
		'details' => $event['desc'],
		'room_id' => $event['room_id'],
	];
}
$return['data'] = $datas;

exit(json_encode($return));
