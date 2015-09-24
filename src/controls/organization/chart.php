<?php
use Intra\Model\LightFileModel;
use Intra\Service\Ridi;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

if (!Ridi::isRidiIP()) {
	throw new Exception('권한이 없습니다.');
}

$filebag = new LightFileModel('organization');

$response = BinaryFileResponse::create($filebag->getLocation('recent'));
$response->prepare(Request::createFromGlobals());
$response->send();

exit;