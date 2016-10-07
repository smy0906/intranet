<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\SupportRowService;

$request = $this->getRequest();
$target = $request->get('target');
$id = $request->get('id');

return SupportRowService::del($target, $id);
