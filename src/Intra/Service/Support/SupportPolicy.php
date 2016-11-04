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
use Intra\Service\Support\Column\SupportColumnDatetime;
use Intra\Service\Support\Column\SupportColumnFile;
use Intra\Service\Support\Column\SupportColumnMoney;
use Intra\Service\Support\Column\SupportColumnMutual;
use Intra\Service\Support\Column\SupportColumnReadonly;
use Intra\Service\Support\Column\SupportColumnRegisterUser;
use Intra\Service\Support\Column\SupportColumnTeam;
use Intra\Service\Support\Column\SupportColumnText;
use Intra\Service\Support\Column\SupportColumnTextDetail;
use Intra\Service\Support\Column\SupportColumnWorker;
use Intra\Service\User\UserConstant;
use Intra\Service\User\UserDto;

class SupportPolicy
{
	const TYPE_DEVICE = 'device';
	const TYPE_FAMILY_EVENT = 'family_event';
	const TYPE_BUSINESS_CARD = 'business_card';
	const TYPE_DEPOT = 'depot';
	const TYPE_GIFT_CARD = 'gift_card';

	/**
	 * @var $column_fields SupportColumn[][]
	 */
	private static $column_fields;
	private static $column_titles;

	/**
	 * @param $target
	 *
	 * @return SupportColumn[]
	 */
	public static function getColumnFields($target)
	{
		self::initColumnFields();
		return self::$column_fields[$target];
	}

	public static function getColumnFieldsTestUserDto($target, $self)
	{
		self::initColumnFields();
		$return_columns = self::$column_fields[$target];
		foreach ($return_columns as $key => $return_column) {
			if (!$return_column->isVisible($self)) {
				unset($return_columns[$key]);
			}
		}

		return $return_columns;
	}

	/**
	 * @param $target
	 *
	 * @return SupportColumn[]
	 */
	public static function getColumnTitle($target)
	{
		self::initColumnFields();
		return self::$column_titles[$target];
	}

	public static function getColumn($target, $key)
	{
		foreach (self::getColumnFields($target) as $column) {
			if ($column->key == $key) {
				return $column;
			}
		}
		throw new \Exception('invalid column ' . $target . ', ' . $key);
	}

	private static function initColumnFields()
	{
		self::$column_titles = [
			self::TYPE_DEVICE => '기기 설치/장애 문의',
			self::TYPE_FAMILY_EVENT => '경조 지원',
			self::TYPE_BUSINESS_CARD => '명함 신청',
			self::TYPE_DEPOT => '구매 요청',
			self::TYPE_GIFT_CARD => '상품권 제작',
		];

		$callback_is_human_manage_team = function (UserDto $user_dto) {
			return $user_dto->team == UserConstant::TEAM_HUMAN_MANAGE;
		};
		self::$column_fields = [
			self::TYPE_DEVICE => [
				'일련번호' => new SupportColumnReadonly('uuid'),
				'일련번호2' => new SupportColumnReadonly('id'),
				'요청일' => new SupportColumnReadonly('reg_date'),
				'요청자' => new SupportColumnRegisterUser('uid'),
				'인사팀 처리' => new SupportColumnComplete('is_completed', $callback_is_human_manage_team),
				'인사팀 처리자' => new SupportColumnCompleteUser('completed_uid', 'is_completed'),
				'인사팀 처리시각' => new SupportColumnCompleteDatetime('completed_datetime', 'is_completed'),
				'귀속부서' => new SupportColumnTeam('team'),
				'구분' => new SupportColumnCategory('category', ['전산 장애문의', 'SW 설치문의', '기타 장애문의']),
				'상세내용' => new SupportColumnText('detail', '', '상세내용'),
				'조치희망일' => new SupportColumnDate('request_date', date('Y/m/d'), true),
				'비고' => new SupportColumnText('note', '', '비고'),
			],
			self::TYPE_FAMILY_EVENT => [
				'일련번호' => new SupportColumnReadonly('uuid'),
				'일련번호2' => new SupportColumnReadonly('id'),
				'요청일' => new SupportColumnReadonly('reg_date'),
				'요청자' => new SupportColumnRegisterUser('uid'),
				'승인' => new SupportColumnAccept('is_accepted'),
				'승인자' => new SupportColumnAcceptUser('accept_uid', 'is_accepted'),
				'승인시각' => new SupportColumnAcceptDatetime('accepted_datetime', 'is_accepted'),
				'인사팀 처리' => new SupportColumnComplete('is_completed', $callback_is_human_manage_team),
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
				'분류 상세' => (new SupportColumnText('category_detail'))->placeholder('나리디님 결혼'),
				'경조일자' => new SupportColumnDate('request_date', date('Y/m/d'), true),
				'경조금' => (new SupportColumnMoney('cash'))->placeholder('미입력시 자동입력'),
				'화환 종류' => new SupportColumnCategory('flower_category', ['미선택시 자동선택', '화환', '과일바구니', '조화', '기타']),
				'화환 상세' => new SupportColumnTextDetail('flower_category_detail', 'flower_category', '기타'),
				'화환 수령자' => new SupportColumnText('flower_receiver', '', '홍길동'),
				'화환 연락처' => new SupportColumnText('flower_call', '', '010-1234-5678'),
				'화환 주소' => new SupportColumnText('flower_address'),
				'화환 도착일시' => (new SupportColumnDatetime('flower_datetime'))->placeholder('2016-01-02 07:10'),
				'비고' => new SupportColumnText('note', '', '비고'),
			],
			self::TYPE_BUSINESS_CARD => [
				'일련번호' => new SupportColumnReadonly('uuid'),
				'일련번호2' => new SupportColumnReadonly('id'),
				'요청일' => new SupportColumnReadonly('reg_date'),
				'요청자' => new SupportColumnRegisterUser('uid'),
				'승인' => new SupportColumnAccept('is_accepted'),
				'승인자' => new SupportColumnAcceptUser('accept_uid', 'is_accepted'),
				'승인시각' => new SupportColumnAcceptDatetime('accepted_datetime', 'is_accepted'),
				'인사팀 처리' => new SupportColumnComplete('is_completed', $callback_is_human_manage_team),
				'인사팀 처리자' => new SupportColumnCompleteUser('completed_uid', 'is_completed'),
				'인사팀 처리시각' => new SupportColumnCompleteDatetime('completed_datetime', 'is_completed'),
				'대상자' => new SupportColumnMutual(
					'receiver_area',
					[
						'직원' => ['대상자(직원)'],
						'현재 미입사' => ['대상자(현재 미입사)'],
					]
				),
				'대상자(직원)' => new SupportColumnWorker('receiver_uid'),
				'대상자(현재 미입사)' => new SupportColumnText('name', '', '홍길동'),
				'영문명' => new SupportColumnText('name_in_english', '', 'Gildong Hong'),
				'부서명' => new SupportColumnTeam('team'),
				'직급(한글)' => new SupportColumnText('grade_korean'),
				'직급(영문)' => new SupportColumnText('grade_english'),
				'PHONE(내선번호)' => new SupportColumnText('call'),
				'FAX' => new SupportColumnText('fax', '02-565-0332'),
				'주소' => new SupportColumnCategory('address', ['어반벤치빌딩 10층', '어반벤치빌딩 11층']),
				'수량' => new SupportColumnCategory('count', [50, 100, 150, 200, '기타 - 50매 단위']),
				'수량(기타)' => new SupportColumnTextDetail('count_detail', 'count', '기타 - 50매 단위'),
				'제작(예정)일' => (new SupportColumnDate('date', '', true))->placeholder('미입력시 월말진행'),
			],
			self::TYPE_DEPOT => [
				'일련번호' => new SupportColumnReadonly('uuid'),
				'일련번호2' => new SupportColumnReadonly('id'),
				'요청일' => new SupportColumnReadonly('reg_date'),
				'요청자' => new SupportColumnRegisterUser('uid'),
				'승인' => new SupportColumnAccept('is_accepted'),
				'승인자' => new SupportColumnAcceptUser('accept_uid', 'is_accepted'),
				'승인시각' => new SupportColumnAcceptDatetime('accepted_datetime', 'is_accepted'),
				'인사팀 처리' => new SupportColumnComplete('is_completed', $callback_is_human_manage_team),
				'인사팀 처리자' => new SupportColumnCompleteUser('completed_uid', 'is_completed'),
				'인사팀 처리시각' => new SupportColumnCompleteDatetime('completed_datetime', 'is_completed'),
				'사용자' => new SupportColumnMutual(
					'receiver_area',
					[
						'직원' => ['사용자(직원)'],
						'현재 미입사' => ['사용자(현재 미입사)'],
					]
				),
				'사용자(직원)' => new SupportColumnWorker('receiver_uid'),
				'사용자(현재 미입사)' => new SupportColumnText('name'),
				'분류' => new SupportColumnCategory('category', [
					'일반구매 (사무용품, IT비품, 기타 소모품 등)',
					'데스크탑 (개발/UX)',
					'데스크탑 (디자이너)',
					'노트북 (일반)',
					'MAC (IMAC)',
					'MAC (MACBOOK)',
					'MAC (MAC MINI)',
					'모니터 (24인치)',
					'모니터 (27인치)',
					'기기 (테블릿, 와콤 등)'
				]),
				'상세내역' => new SupportColumnText('detail'),
				'세팅(예정)일' => new SupportColumnDate('request_date', date('Y/m/d', strtotime('+7 day')), true),
				'비고' => new SupportColumnText('note', '', '비고'),
				'보유여부' => (new SupportColumnCategory('is_exist', ['재고', '신규구매']))->isVisibleIf($callback_is_human_manage_team),
				'라벨번호' => (new SupportColumnText('label'))->isVisibleIf($callback_is_human_manage_team),
			],
			self::TYPE_GIFT_CARD => [
				'일련번호' => new SupportColumnReadonly('uuid'),
				'일련번호2' => new SupportColumnReadonly('id'),
				'요청일' => new SupportColumnReadonly('reg_date'),
				'요청자' => new SupportColumnRegisterUser('uid'),
				'승인' => new SupportColumnAccept('is_accepted'),
				'승인자' => new SupportColumnAcceptUser('accept_uid', 'is_accepted'),
				'승인시각' => new SupportColumnAcceptDatetime('accepted_datetime', 'is_accepted'),
				'인사팀 처리' => new SupportColumnComplete('is_completed', $callback_is_human_manage_team),
				'인사팀 처리자' => new SupportColumnCompleteUser('completed_uid', 'is_completed'),
				'인사팀 처리시각' => new SupportColumnCompleteDatetime('completed_datetime', 'is_completed'),
				'권종' => new SupportColumnCategory('category', ['포인트']),
				'금액' => new SupportColumnMoney('cash'),
				'유효기간' => new SupportColumnDate('expire_date', date('Y/m/d', strtotime('+7 day'))),
				'수량' => new SupportColumnMoney('count'),
				'난수번호 파일' => new SupportColumnFile('random_file'),
				'제작(예정)일' => new SupportColumnDate('request_date', date('Y/m/d', strtotime('+7 day')), true),
				'비고' => new SupportColumnText('note', '', '비고'),
				'이미지파일' => new SupportColumnFile('image_file'),
			],
		];
	}
}
