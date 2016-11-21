<?php

namespace Intra\Service\Menu;

use Intra\Config\Config;
use Intra\Service\Auth\ExceptTaAuth;
use Intra\Service\Auth\OnlyPressManager;
use Intra\Service\Auth\OnlyUserManager;
use Intra\Service\Auth\PublicAuth;
use Intra\Service\User\UserSession;
use Silex\Application;
use Twig_Environment;

class MenuService
{
	public static function addToSilexTwig(Application $app)
	{
		list($left_menu_list, $right_menu_list) = self::getMenuLinkList();
		$app->extend('twig', function (Twig_Environment $twig, $app) use ($left_menu_list, $right_menu_list) {
			$twig->addGlobal('leftMenuList', $left_menu_list);
			$twig->addGlobal('rightMenuList', $right_menu_list);
			return $twig;
		});
	}

	/**
	 * @return array
	 */
	public static function getMenuLinkList():array
	{
		if (UserSession::isLogined()) {
			if (Config::$domain == 'ridi.com') {
				$left_menu_list = [
					new Link('직원찾기', '/users', new PublicAuth),
					new Link('리디 생활 가이드', 'https://ridicorp.sharepoint.com/intranet/SitePages/%EB%A6%AC%EB%94%94%20%EC%83%9D%ED%99%9C%20%EA%B0%80%EC%9D%B4%EB%93%9C.aspx', null, '_blank'),
					new Link('전사 주간 업무 요약', '/weekly', new ExceptTaAuth, '_blank'),
					new Link('회의실', '/Rooms', new PublicAuth),
					new Link('포커스룸', '/Rooms?type=focus'),
					new Link('휴가신청', '/holidays', new PublicAuth),
					'지원요청(테스트 중)' => [
						new Link('기기 설치/장애 문의', '/support/device'),
						new Link('경조 지원', '/support/family_event'),
						new Link('명함 신청', '/support/business_card'),
						new Link('구매 요청', '/support/depot'),
						new Link('상품권 제작', '/support/gift_card'),
					],
					new Link('결제요청', '/payments', (new ExceptTaAuth)->accept(['hr.ta'])),
					new Link('비용정산', '/receipts', new PublicAuth),
					new Link('급여관리', 'http://htms.himgt.net', new ExceptTaAuth, '_blank'),
					new Link('보도자료 관리', '/press', new OnlyPressManager),
					new Link('조직도', '/organization/chart', new ExceptTaAuth, '_blank'),
				];
				if (!Config::$is_dev) {
					unset($left_menu_list['지원요청(테스트 중)']);
				}
			} else {
				$left_menu_list = [
					new Link('공지사항', '/posts/notice'),
					new Link('휴가신청', '/holidays'),
					new Link('비용정산', '/receipts'),
					new Link('회의실', '/Rooms'),
					new Link('포커스룸', '/Rooms?type=focus'),
					new Link('리디 생활 가이드', '/users'),
					new Link('급여관리', 'http://htms.himgt.net', new ExceptTaAuth, '_blank'),
				];
			}

			$right_menu_list = [
				new Link('직원목록', '/users/list', new OnlyUserManager, null, 'list'),
				new Link('로그아웃', '/usersession/logout', new PublicAuth, null, 'log-out'),
			];
			return [$left_menu_list, $right_menu_list];
		} else {
			$left_menu_list = [];
			$right_menu_list = [];
			return [$left_menu_list, $right_menu_list];
		}
	}
}
