<?php

namespace Intra\Controller;

use Intra\Config\Config;
use Intra\Service\Menu\MenuService;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Twig_Environment;

class RootController implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$app->extend('twig', function (Twig_Environment $twig, $app) {
			$twig->addGlobal('globalDomain', Config::$domain);
			$twig->addGlobal('sentryPublicKey', Config::$sentry_public_key);
			return $twig;
		});
		MenuService::addToSilexTwig($app);

		return $app['controllers_factory'];
	}
}
