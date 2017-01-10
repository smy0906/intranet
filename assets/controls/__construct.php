<?php
/** @var $this Intra\Core\Control */
use Intra\Config\Config;
use Intra\Service\Menu\MenuService;
use Intra\Service\User\UserPolicy;

$request = $this->getRequest();

$response = UserPolicy::assertRestrictedPath($request);
if ($response) {
    return $response;
}

list($left_menu_list, $right_menu_list) = MenuService::getMenuLinkList();

$response = $this->getResponse();
$response->add(
    [
        'globalDomain' => Config::$domain,
        'leftMenuList' => $left_menu_list,
        'rightMenuList' => $right_menu_list,
        'sentryPublicKey' => Config::$sentry_public_key,
    ]
);
