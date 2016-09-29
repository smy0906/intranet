<?php

namespace Intra\Service\Support;

use Intra\Service\Support\Column\SupportColumn;
use Intra\Service\Support\Column\SupportColumnAccept;
use Intra\Service\Support\Column\SupportColumnAcceptDatetime;
use Intra\Service\Support\Column\SupportColumnAcceptUser;
use Intra\Service\Support\Column\SupportColumnCategory;
use Intra\Service\Support\Column\SupportColumnComplete;
use Intra\Service\Support\Column\SupportColumnCompleteDatetime;
use Intra\Service\Support\Column\SupportColumnCompleteUser;
use Intra\Service\Support\Column\SupportColumnDate;
use Intra\Service\Support\Column\SupportColumnReadonly;
use Intra\Service\Support\Column\SupportColumnRegisterUser;
use Intra\Service\Support\Column\SupportColumnTeam;
use Intra\Service\Support\Column\SupportColumnText;
use Intra\Service\User\UserConstant;
use Intra\Service\User\UserDto;

class SupportPolicy
{
	/**
	 * @param $target
	 *
	 * @return SupportColumn[]
	 */
	public static function getColumns($target)
	{
		$columns = [
			'device' => [
				'요청일' => new SupportColumnReadonly('reg_date'),
				'요청자' => new SupportColumnRegisterUser('uid'),
				'인사팀 처리' => new SupportColumnComplete('is_completed', function (UserDto $user_dto) {
					return $user_dto->team_detail == UserConstant::TEAM_DETAIL_HUMAN_MANAGE;
				}),
				'인사팀 처리자' => new SupportColumnCompleteUser('completed_uid', 'is_completed'),
				'인사팀 처리시각' => new SupportColumnCompleteDatetime('completed_datetime', 'is_completed'),
				'귀속부서' => new SupportColumnTeam('team'),
				'구분' => new SupportColumnCategory('category', ['전산 장애문의', 'SW 설치문의', '기타 장애문의']),
				'상세내용' => new SupportColumnText('detail', '', '상세내용'),
				'조치희망일' => new SupportColumnDate('request_date', date('Y/m/d'), true),
				'비고' => new SupportColumnText('note', '', '비고'),
			],
			'family_event' => [
				'요청일' => new SupportColumnReadonly('reg_date'),
				'요청자' => new SupportColumnRegisterUser('uid'),
				'승인' => new SupportColumnAccept('is_accepted'),
				'승인자' => new SupportColumnAcceptUser('accept_uid', 'is_accepted'),
				'승인시각' => new SupportColumnAcceptDatetime('accepted_datetime', 'is_accepted'),
				'인사팀 처리' => new SupportColumnComplete('is_completed', function (UserDto $user_dto) {
					return $user_dto->team_detail == UserConstant::TEAM_DETAIL_HUMAN_MANAGE;
				}),
				'인사팀 처리자' => new SupportColumnCompleteUser('completed_uid', 'is_completed'),
				'인사팀 처리시각' => new SupportColumnCompleteDatetime('completed_datetime', 'is_completed'),
				'대상자' => new SupportColumnMutual(
					'receiver_area',
					[
						'외부' => ['대상 업체(외부)', '대상 업체 담당자(외부)', '거래처 경조 사유(외부)'],
						'내부' => ['귀속부서', '대상자(직원)', '분류', '분류 상세']
					]
				),
				'대상 업체(외부)' => new SupportColumnText('outer_receiver_business'),
				'대상 업체 담당자(외부)' => new SupportColumnText('outer_receiver_name'),
				'거래처 경조 사유(외부)' => new SupportColumnText('outer_receiver_detail'),
				'귀속부서' => new SupportColumnTeam('team'),
				'대상자(직원)' => new SupportColumnWorker('receiver_worker_uid'),
				'분류' => new SupportColumnCategory('category', [
					'졸업',
					'결혼',
					'자녀출생',
					'장기근속(3년)',
					'사망-부모 (배우자 부모 포함)',
					'사망-조부모 (배우자 조부모 포함)',
					'기타'
				]),
				'분류 상세' => new SupportColumnTextDetail('category_detail', 'category', '기타'),
				'경조일자' => new SupportColumnDate('request_date', date('Y/m/d'), true),
				'경조금' => new SupportColumnMoney('cash'),
				'화환 종류' => new SupportColumnCategory('flower_category', ['자동선택', '화환', '과일바구니', '조화', '기타']),
				'화환 수령자' => new SupportColumnText('flower_receiver'),
				'화환 연락처' => new SupportColumnText('flower_call'),
				'화환 주소' => new SupportColumnText('flower_address'),
				'화환 도착일시' => new SupportColumnDate('flower_date', date('Y/m/d')),
				'비고' => new SupportColumnText('note', '', '비고'),
			],
			'business_card',
			'depot',
			'gift_card',
		];
		return $columns[$target];
	}

	public static function getColumnAction($target)
	{
		$actions = [];
		return $actions[$target];
	}
}
