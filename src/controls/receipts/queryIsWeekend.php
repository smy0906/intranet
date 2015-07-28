<?php
/** @var $this Intra\Core\Control */
use Intra\Service\UserReceipts;

$request = $this->getRequest();
$month = $request->get('month');
$day = $request->get('day');

return UserReceipts::queryWeekend($month, $day);
