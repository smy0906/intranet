<?php
use Intra\Model\LightFileModel;
use Intra\Service\Ridi;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

if (!Ridi::isRidiIP() || UserSession::isTa()) {
	throw new Exception('권한이 없습니다.');
}

$filebag = new LightFileModel('organization');

return BinaryFileResponse::create($filebag->getLocation('recent'));
