<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\UserEditService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

$request = $this->getRequest();
$uid = $request->get('uid');

$file =  UserEditService::getImage($uid);
if ($file !== null) {
	return BinaryFileResponse::create($file, 200);

} else {
	return Response::create('file not exist', 404);
}
