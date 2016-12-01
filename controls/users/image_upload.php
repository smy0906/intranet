<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserFileService;
use Intra\Service\User\UserSession;
use Intra\Service\User\UserDtoHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

$request = $this->getRequest();
$file = $request->files->get('files')[0];

if (UserFileService::addImage($file)) {
	$dto = UserSession::getSelfDto();
	$uid = $dto->uid;

	$user = new UserDtoHandler($dto);
	$user->updateByKey('image', '/users/image?uid=' . $uid);

	return JsonResponse::create('success');

} else {
	return JsonResponse::create('file upload failed', 500);
}
