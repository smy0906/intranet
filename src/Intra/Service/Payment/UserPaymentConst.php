<?php
namespace Intra\Service\Payment;

use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

class UserPaymentConst
{
	public static function getConstValueByKey($key)
	{
		if ($key == 'manager_uid') {
			$ret = [];
			foreach (UserService::getAvailableUserDtos() as $user) {
				$ret[$user->uid] = $user->name;
			}
			return json_encode($ret);
		}
		if (!UserPaymentConst::isExistByKey($key)) {
			return null;
		}
		$ret = [];
		foreach (UserPaymentConst::getByKey($key) as $v) {
			$ret[$v] = $v;
		}
		return json_encode($ret);
	}

	public static function get()
	{
		$const = [];
		$const['team'] = [
			'단행본 / 개발센터 / 데이터팀',
			'단행본 / 개발센터 / 뷰어팀',
			'단행본 / 개발센터 / 스토어팀',
			'단행본 / 개발센터 / 플랫폼팀',
			'단행본 / 운영지원센터 / CC / PQ팀',
			'단행본 / 운영지원센터 / 사업분석팀',
			'단행본 / 운영지원센터 / 운영지원팀',
			'단행본 / Growth팀',
			'단행본 / 디바이스팀',
			'단행본 / 디자인팀',
			'단행본 / 콘텐츠운영1팀',
			'단행본 / 콘텐츠운영2팀',
			'스튜디오D',
			'연재',
			'전사공통',
		];
		$const['product'] = ['리디북스', '페이퍼샵', '공통'];
		$const['tax'] = ['Y', 'N', 'N/A'];
		$const['paytype'] = ['현금', '법인카드'];
		$const['status'] = ['결제 대기중', '결제 완료'];
		$const['category'] = [
			'상품매입 (페이퍼샵 판매용 상품 매입 비용)',
			'운반비 (택배, 퀵서비스 이용대금)',
			'잡급 (로맨스, 판무 가이드 알바 급여)',
			'복리후생비',
			'소모품비 (페이퍼샵 판매를 위한 포장자재, 기타 소모품)',
			'자산(PC, 공사대금 등)',
			'도서구입 및 인쇄비 (쿠폰, 상품권, 명함등 인쇄, 도서구입비)',
			'저작권료 (마케팅용X, 콘텐츠 매절, 저작권 양수)',
			'저작권료 (마케팅용O, 콘텐츠 매절)',
			'선인세',
			'제작비용 (표지, 일러스트, 편집료 등)',
			'광고선전비',
			'지급수수료',
		];
		if (UserPolicy::isPaymentAdmin(UserSession::getSelfDto())) {
			$const['category'][] = '기타';
		}
		$const['pay_date'] = ['선택해주세요', '7일', '10일', '25일', '월말일', '긴급'];

		return $const;
	}

	public static function isExistByKey($key)
	{
		return isset(self::get()[$key]);
	}

	public static function getByKey($key)
	{
		return self::get()[$key];
	}
}
