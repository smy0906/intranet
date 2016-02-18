<?php
/** @var $this Intra\Core\Control */

use Intra\Service\User\Users;

$request = $this->getRequest();

$id = $request->get('userid');
$key = $request->get('key');
$value = $request->get('value');

$user = Users::getById($id);
$user->setExtra($key, $value);
return 1;
