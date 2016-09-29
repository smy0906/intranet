<?php
/** @var $this Intra\Core\Control */
use Intra\Service\Receipt\UserReceipts;

$request = $this->getRequest();
$month = $request->get('month');
$day = $request->get('day');

return UserReceipts::queryWeekend($month, $day);
