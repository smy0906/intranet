<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\UserSupport;

$request = $this->getRequest();
$target = $request->get('target');
$id = $request->get('id');
$key = $request->get('key');
$value = $request->get('value');

$type = $request->get('type');
if ($type == 'complete') {
	return UserSupport::complete($target, $id, $key);
}
return UserSupport::edit($target, $id, $key, $value);
