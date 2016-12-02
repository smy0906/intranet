<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserEditService;
use Symfony\Component\HttpFoundation\JsonResponse;

$request = $this->getRequest();
$file = $request->files->get('files')[0];

if (UserEditService::updateImageFile($file) !== null) {
	return JsonResponse::create('success');

} else {
	return JsonResponse::create('file upload failed', 500);
}
