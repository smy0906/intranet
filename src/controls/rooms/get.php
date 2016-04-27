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
				<room_id><![CDATA[' . htmlspecialchars($e['room_id']) . ']]></room_id>
			</event>
			';
}
$return .= '</data>';

header("content-type: text/xml");
echo $return;
exit;
