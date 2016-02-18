<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\Users;

$request = $this->getRequest();
Users::join($request);
return '1';
