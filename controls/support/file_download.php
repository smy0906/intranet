<?php
/** @var $this Intra\Core\Control */

use Intra\Core\MsgException;
use Intra\Service\Support\SupportFileService;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$target = $request->get('target');
$fileid = $request->get('fileid');
if (!intval($fileid)) {
	throw new MsgException("invalid fileid");
}

return SupportFileService::downloadFile($self, $target, $fileid);
