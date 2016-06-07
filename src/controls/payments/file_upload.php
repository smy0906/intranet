<?php
/** @var $this Intra\Core\Control */

use Intra\Core\MsgException;
use Intra\Service\Payment\UserPaymentService;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\File\UploadedFile;

$request = $this->getRequest();
$self = UserSession::getSelfDto();

$paymentid = $request->get('paymentid');
if (!intval($paymentid)) {
	throw new MsgException("invalid paymentid");
}
/**
 * @var $file UploadedFile
 */
$file = $request->files->get('files')[0];

if (UserPaymentService::addFiles($paymentid, $file)) {

} else {

}
