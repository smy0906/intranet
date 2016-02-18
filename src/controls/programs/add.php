<?php
/** @var $this Intra\Core\Control */

use Intra\Service\IntraDb;

$request = $this->getRequest();

$key = $request->get('key');
$value = $request->get('value');
$value = urldecode($value);
var_dump($value);

$db = IntraDb::getGnfDb();
$insert = [
	$key => $value
];
if ($db->sqlInsert($key . 's', $insert)) {
	echo "추가 되었습니다";
} else {
	echo "추가 되지않았습니다";
}
exit;
