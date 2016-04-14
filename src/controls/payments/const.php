<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Payment\UserPaymentService;
use Intra\Service\Payment\UserPaymentConst;
use Intra\Service\User\UserSession;

$request = $this->getRequest();
$key = $request->get('key');

return UserPaymentConst::getConstValueByKey($key);
