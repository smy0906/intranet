<?php

/** @var $this Intra\Core\Control */

$request = $this->getRequest();

$room_id = $request->get('room_id');

$db = \Intra\Service\IntraDb::getGnfDb();
$where = ['room_id' => $room_id, 'deleted' => 0];
$events = $db->sqlDicts('select * from room_events where ?', sqlWhere($where));

$return = '';
$return .= '<' . '?xml version=\'1.0\' encoding=\'utf-8\'?' . '>';
$return .= '<data>';
foreach ($events as $e) {
	$return .= '
			<event id="' . $e['id'] . '">
				<start_date>' . $e['from'] . '</start_date>
				<end_date>' . $e['to'] . '</end_date>
				<text><![CDATA[' . htmlspecialchars($e['desc']) . ']]></text>
				<details><![CDATA[' . htmlspecialchars($e['desc']) . ']]></details>
			</event>
			';
}
$return .= '</data>';

header("content-type: text/xml");
echo $return;
exit;
