<?php
/** @var $this Intra\Core\Control */

use Intra\Core\MsgException;
use Intra\Service\Support\SupportFileService;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\Response;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$target = $request->get('target');
$fileid = $request->get('fileid');
if (!intval($fileid)) {
	throw new MsgException("invalid fileid");
}


if (SupportFileService::deleteFile($self, $target, $fileid)) {
	return Response::create('1');
} else {
	return Response::create('삭제실패했습니다.');
}
