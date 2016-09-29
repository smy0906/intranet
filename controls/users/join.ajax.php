<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserService;

$request = $this->getRequest();
UserService::join($request);
return '1';
