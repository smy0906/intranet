<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\SupportRowService;

$request = $this->getRequest();
$target = $request->get('target');
$id = $request->get('id');
$key = $request->get('key');
$value = $request->get('value');

$type = $request->get('type');
if ($type == 'complete') {
	return SupportRowService::complete($target, $id, $key);
}
return SupportRowService::edit($target, $id, $key, $value);
