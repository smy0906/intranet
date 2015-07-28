<?php
/** @var $this Intra\Core\Control */
use Symfony\Component\HttpFoundation\RedirectResponse;

$request = $this->getRequest();

\Intra\Service\UserSession::logout();

return new RedirectResponse('/usersession/login');
