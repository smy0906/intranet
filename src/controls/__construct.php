<?php
/** @var $this Intra\Core\Control */
use Intra\Config\Config;
use Intra\Service\UserSession;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$request = $this->getRequest();

$free_to_login_path = array(
	'/usersession/login',
	'/usersession/login.azure',
	'/users/join',
	'/programs/insert',
	'/programs/list',
	'/api/ridibooks_ids',
);

$is_free_to_login = in_array($request->getPathInfo(), $free_to_login_path);
if (!$is_free_to_login && !UserSession::isLogined()) {
	if ($request->isXmlHttpRequest()) {
		return new Response('login error');
	} else {
		return new RedirectResponse('/usersession/login');
	}
}

$response = $this->getResponse();
$response->add(
	array(
		'globalDomain' => Config::$domain,
        'pressManager' => UserSession::isPressManager()
	)
);
