<?php
/** @var $this Intra\Core\Control */

use Intra\Core\MsgException;
use Intra\Service\Payment\UserPaymentService;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\File\UploadedFile;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$fileid = $request->get('fileid');
if (!intval($fileid)) {
	throw new MsgException("invalid fileid");
}

return UserPaymentService::downloadFile($self, $fileid);
