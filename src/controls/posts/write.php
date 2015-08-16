<?php
/** @var $this Intra\Core\Control */
$request = $this->getRequest();
$group = $request->get('group');

return array('group' => $group);
