<?php

/** @var $this Intra\Core\Control */

use Symfony\Component\HttpFoundation\JsonResponse;

$db = \Intra\Service\IntraDb::getGnfDb();

$request = $this->getRequest();
$from = $request->get('from');
$to = $request->get('to');
$room_ids = $request->get('room_ids');
$room_ids = explode(',', $room_ids);

if (count($room_ids) == 0) {
	return new JsonResponse([]);
}

$where = [
	'deleted' => 0,
	'from' => sqlGreaterEqual($from),
	'to' => sqlLesser($to),
	'room_id' => $room_ids,
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

return new JsonResponse($return);
