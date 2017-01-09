<?php
/** @var $this Intra\Core\Control */
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserSession;

$is_receipts_admin = UserPolicy::isReceiptsAdmin(UserSession::getSelfDto());
$response = $this->getResponse();
$response->add(
    [
        'isAdmin' => $is_receipts_admin,
    ]
);
