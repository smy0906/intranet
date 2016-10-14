<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\SupportDto;
use Intra\Service\Support\SupportPolicy;
use Intra\Service\Support\SupportRowService;
use Intra\Service\User\UserDtoFactory;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserSession;

$self = UserSession::getSelfDto();

$request = $this->getRequest();
$target = $request->get('target');

$columns = SupportPolicy::getColumnFields($target);
$uid = $request->get('uid');
if (!intval($uid) || !UserPolicy::isSupportAdmin($self)) {
	$uid = $self->uid;
}

$support_dto = SupportDto::importFromAddRequest($request, $uid, $columns);
$target_user_dto = UserDtoFactory::createByUid($uid);

return SupportRowService::add($target_user_dto, $support_dto);
