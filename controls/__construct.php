<?php
/** @var $this Intra\Core\Control */
use Intra\Config\Config;
use Intra\Service\Auth\ExceptTaAuthChecker;
use Intra\Service\Auth\OnlyPressManager;
use Intra\Service\Auth\OnlyUserManager;
use Intra\Service\Auth\PublicAuth;
use Intra\Service\Menu\Link;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$request = $this->getRequest();

$free_to_login_path = [
	'/usersession/login',
	'/usersession/login.azure',
	'/users/join',
	'/programs/insert',
	'/programs/list',
	'/api/ridibooks_ids',
	'/press/list'
];

$is_free_to_login = in_array($request->getPathInfo(), $free_to_login_path);
if (!$is_free_to_login && !UserSession::isLogined()) {
	if ($request->isXmlHttpRequest()) {
		return new Response('login error');
	} else {
		return new RedirectResponse('/usersession/login');
	}
}

if (Config::$domain == 'ridi.com') {
	$left_menu_list = [
		new Link('직원찾기', '/users', PublicAuth::class),
		new Link('리디 생활 가이드', 'https://ridicorp.sharepoint.com/intranet/SitePages/%EB%A6%AC%EB%94%94%20%EC%83%9D%ED%99%9C%20%EA%B0%80%EC%9D%B4%EB%93%9C.aspx', null, '_blank'),
		new Link('전사 주간 업무 요약', '/weekly', ExceptTaAuthChecker::class, '_blank'),
		new Link('회의실', '/rooms', PublicAuth::class),
		new Link('포커스룸', '/rooms?type=focus'),
		new Link('휴가신청', '/holidays', PublicAuth::class),
		'지원요청(테스트 중)' => [
			new Link('기기 설치/장애 문의', '/support/device'),
			new Link('경조 지원', '/support/family_event'),
			new Link('명함 신청', '/support/business_card'),
			new Link('구매 요청', '/support/depot'),
			new Link('상품권 제작', '/support/gift_card'),
		],
		new Link('결제요청', '/payments'),
		new Link('비용정산', '/receipts', PublicAuth::class),
		new Link('급여관리', 'http://htms.himgt.net', ExceptTaAuthChecker::class, '_blank'),
		new Link('보도자료 관리', '/press', OnlyPressManager::class),
		new Link('조직도', '/organization/chart', ExceptTaAuthChecker::class, '_blank'),
	];
	if (!Config::$is_dev) {
		unset($left_menu_list['지원요청(테스트 중)']);
	}
} else {
	$left_menu_list = [
		new Link('공지사항', '/posts/notice'),
		new Link('휴가신청', '/holidays'),
		new Link('비용정산', '/receipts'),
		new Link('회의실', '/rooms'),
		new Link('포커스룸', '/rooms?type=focus'),
		new Link('리디 생활 가이드', '/users'),
		new Link('급여관리', 'http://htms.himgt.net', ExceptTaAuthChecker::class, '_blank'),
	];
}

$right_menu_list = [
	new Link('직원목록', '/users/list', OnlyUserManager::class, null, 'list'),
	new Link('로그아웃', '/usersession/logout', PublicAuth::class, null, 'log-out'),
];

$response = $this->getResponse();
$response->add(
	[
		'globalDomain' => Config::$domain,
		'left_menu_list' => $left_menu_list,
		'right_menu_list' => $right_menu_list,
		'sentryPublicKey' => Config::$sentry_public_key,
	]
);
