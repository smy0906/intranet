<?php
/** @var $this Intra\Core\Control */

use Intra\Model\UserFactory;
use Intra\Service\User\UserSession;

$self = UserSession::getSelf();
if ($self->isSuperAdmin()) {
	$replaceable = true;
}

return array(
	'replaceable' => $replaceable,
	'users' => UserFactory::getAvailableUsers(),
	'allUsers' => UserFactory::getAllUsers(),
	'allCurrentUsers' => UserFactory::getAvailableUsers()
);
