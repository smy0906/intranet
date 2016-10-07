<?php
/** @var $this Intra\Core\Control */

use Intra\Core\MsgException;
use Intra\Service\Support\SupportFileService;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$target = $request->get('target');
$id = $request->get('id');
$column_key = $request->get('column_key');

if (!intval($id)) {
	throw new MsgException("invalid paymentid");
}
/**
 * @var $file UploadedFile
 */
$file = $request->files->get('files')[0];

if (SupportFileService::addFiles($target, $id, $column_key, $file)) {
	return JsonResponse::create('success');
} else {
	return JsonResponse::create('file upload failed', 500);
}
