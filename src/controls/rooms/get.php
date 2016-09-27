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
$datas = addDefaultReservation($from, $datas);


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

/**
 * @param $from
 * @param $datas
 *
 * @return array
 */
function addDefaultReservation($from, $datas)
{
	//디바이스팀 장기 예약
	if (strtotime('2016/9/20 00:00:00') < strtotime($from) && strtotime($from) < strtotime('2016/10/29 00:00:00')) {
		$datas[] =
			[
				'id' => 0,
				'start_date' => $from . ' 10:00:00',
				'end_date' => $from . ' 19:00:00',
				'text' => '디바이스팀 장기예약',
				'details' => '디바이스팀 장기예약',
				'room_id' => '10',
			];
	}
	//플랫폼팀 주간미팅
	if (date('w', strtotime($from)) == 1) {
		$datas[] =
			[
				'id' => 0,
				'start_date' => $from . ' 11:00:00',
				'end_date' => $from . ' 12:30:00',
				'text' => '[예약자] 박주현 [예약내용] 주간미팅',
				'details' => '[예약자] 박주현 [예약내용] 주간미팅',
				'room_id' => '15',
			];
	}
	//플랫폼팀 일간미팅
	$datas[] =
		[
			'id' => 0,
			'start_date' => $from . ' 18:30:00',
			'end_date' => $from . ' 19:00:00',
			'text' => '[예약자] 박주현 [예약내용] 일간미팅',
			'details' => '[예약자] 박주현 [예약내용] 일간미팅',
			'room_id' => '15',
		];
	return $datas;
}
