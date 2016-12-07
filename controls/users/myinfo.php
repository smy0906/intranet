<?php

use Intra\Service\User\UserSession;

$dto = UserSession::getSelfDto();

return ['info' => $dto];
