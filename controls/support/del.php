<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\UserSupport;

$request = $this->getRequest();
$target = $request->get('target');
$id = $request->get('id');

return UserSupport::del($target, $id);
