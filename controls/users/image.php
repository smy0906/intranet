<?php

use Intra\Service\User\UserFileService;

$request = $this->getRequest();
$key = $request->get('uid');

return UserFileService::getImage($key);
