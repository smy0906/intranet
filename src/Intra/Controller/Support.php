<?php

namespace Intra\Controller;

use Intra\Core\MsgException;
use Intra\Lib\Response\CsvResponse;
use Intra\Service\Support\Column\SupportColumnCategory;
use Intra\Service\Support\Column\SupportColumnTeam;
use Intra\Service\Support\Column\SupportColumnWorker;
use Intra\Service\Support\SupportDto;
use Intra\Service\Support\SupportFileService;
use Intra\Service\Support\SupportPolicy;
use Intra\Service\Support\SupportRowService;
use Intra\Service\Support\SupportViewDtoFactory;
use Intra\Service\User\UserConstant;
use Intra\Service\User\UserDtoFactory;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserSession;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Support implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		/**
		 * @var ControllerCollection $controller_collection
		 */
		$controller_collection = $app['controllers_factory'];

		$controller_collection->get('/{target}', [$this, 'index']);
		$controller_collection->get('/{target}/{type}', [$this, 'index']);
		$controller_collection->get('/{target}/uid/{uid}/yearmonth/{yearmonth}', [$this, 'index']);

		$controller_collection->post('/{target}/add', [$this, 'add']);
		$controller_collection->put('/{target}/id/{id}', [$this, 'edit']);
		$controller_collection->put('/{target}/id/{id}/complete', [$this, 'edit'])->value('type', 'complete');

		$controller_collection->delete('/{target}/id/{id}', [$this, 'del']);
		$controller_collection->get('/{target}/const/{key}', [$this, 'constVaules']);

		$controller_collection->post('/{target}/file_upload', [$this, 'fileUpload']);
		$controller_collection->get('/{target}/file/{fileid}', [$this, 'fileDownload']);
		$controller_collection->delete('/{target}/file/{fileid}', [$this, 'fileDelete']);

		$controller_collection->get('/{target}/download/{type}/{yearmonth}', [$this, 'excelDownload']);

		return $controller_collection;
	}

	public function index(Request $request, Application $app)
	{
		$self = UserSession::getSelfDto();
		$target = $request->get('target');
		$yearmonth = $request->get('yearmonth');
		$uid = $request->get('uid');
		$type = $request->get('type');

		if (!strlen($yearmonth)) {
			$yearmonth = date('Y-m');
		}
		$date = $yearmonth . '-01';
		if (!intval($uid) || !UserPolicy::isSupportAdmin($self)) {
			$uid = $self->uid;
		}

		$prev_yearmonth = date('Y-m', strtotime('-1 month', strtotime($yearmonth)));
		$next_yearmonth = date('Y-m', strtotime('+1 month', strtotime($yearmonth)));

		$columns = SupportPolicy::getColumnFieldsTestUserDto($target, $self);
		$title = SupportPolicy::getColumnTitle($target);
		$const = [
			'teams' => UserConstant::$jeditable_key_list['team'],
			'managers' => UserDtoFactory::createManagerUserDtos(),
			'users' => UserDtoFactory::createAvailableUserDtos(),
		];
		$support_view_dtos = SupportViewDtoFactory::gets($columns, $target, $uid, $date, $type);

		$explain = $this->getExplain($target);

		return $app['twig']->render('support/index.twig', [
			'uid' => $uid,
			'prev_yearmonth' => $prev_yearmonth,
			'yearmonth' => $yearmonth,
			'next_yearmonth' => $next_yearmonth,
			'target' => $target,
			'title' => $title,
			'columns' => $columns,
			'support_view_dtos' => $support_view_dtos,
			'const' => $const,
			'is_admin' => UserPolicy::isSupportAdmin($self),
			'all_users' => UserDtoFactory::createAllUserDtos(),
			'explain' => $explain,
		]);
	}

	public function add(Request $request, Application $app)
	{
		$self = UserSession::getSelfDto();

		$target = $request->get('target');

		$columns = SupportPolicy::getColumnFields($target);
		$uid = $request->get('uid');
		if (!intval($uid) || !UserPolicy::isSupportAdmin($self)) {
			$uid = $self->uid;
		}

		$support_dto = SupportDto::importFromAddRequest($request, $uid, $columns);
		$target_user_dto = UserDtoFactory::createByUid($uid);

		return SupportRowService::add($target_user_dto, $support_dto);
	}

	public function constVaules(Request $request, Application $app)
	{
		$target = $request->get('target');
		$key = $request->get('key');

		$columns = SupportPolicy::getColumnFields($target);
		$return = [];
		foreach ($columns as $column) {
			if ($key == $column->key) {
				if ($column instanceof SupportColumnTeam) {
					foreach (UserConstant::$jeditable_key_list['team'] as $team) {
						$return[$team] = $team;
					}
				} elseif ($column instanceof SupportColumnWorker) {
					foreach (UserDtoFactory::createAvailableUserDtos() as $user_dto) {
						$return[$user_dto->uid] = $user_dto->name;
					}
				} elseif ($column instanceof SupportColumnCategory) {
					foreach ($column->category_items as $category_item) {
						$return[$category_item] = $category_item;
					}
				}
			}
		}
		return new JsonResponse($return);
	}

	public function del(Request $request, Application $app)
	{
		$target = $request->get('target');
		$id = $request->get('id');

		return SupportRowService::del($target, $id);
	}

	public function edit(Request $request, Application $app)
	{
		$target = $request->get('target');
		$id = $request->get('id');
		$key = $request->get('key');
		$value = $request->get('value');

		$type = $request->get('type');
		if ($type == 'complete') {
			return SupportRowService::complete($target, $id, $key);
		}
		return SupportRowService::edit($target, $id, $key, $value);
	}

	public function excelDownload(Request $request, Application $app)
	{
		$self = UserSession::getSelfDto();

		$target = $request->get('target');
		$type = $request->get('type');
		$yearmonth = $request->get('yearmonth');
		if ($type == 'year') {
			$date = date_create($yearmonth . '-01');
			$begin_datetime = (clone $date)->modify("first day of this year");
			$end_datetime = (clone $begin_datetime)->modify("first day of next year");
		} elseif ($type == 'yearmonth') {
			$begin_datetime = date_create($yearmonth . '-01');
			$end_datetime = (clone $begin_datetime)->modify("first day of this month next month");
		} else {
			throw new MsgException('invalid type');
		}

		$columns = SupportPolicy::getColumnFieldsTestUserDto($target, $self);
		$support_view_dtos = SupportViewDtoFactory::getsForExcel($columns, $target, $begin_datetime, $end_datetime);

		$csvs = [];
		$csv_header = [];
		foreach ($columns as $column_name => $column) {
			$csv_header[] = $column_name;
		}
		$csv_header = $this->excelPostworkHeader($csv_header, $target);
		$csvs[] = $csv_header;

		foreach ($support_view_dtos as $support_view_dto) {
			$csv_row = [];
			foreach ($columns as $column_name => $column) {
				$csv_row[$column_name] = $support_view_dto->display_dict[$column->key];
			}
			$csv_row = $this->excelPostworkBody($csv_row, $target);
			$csvs[] = $csv_row;
		}

		return CsvResponse::create($csvs);
	}

	public function fileDelete(Request $request, Application $app)
	{
		$self = UserSession::getSelfDto();

		$target = $request->get('target');
		$fileid = $request->get('fileid');
		if (!intval($fileid)) {
			throw new MsgException("invalid fileid");
		}


		if (SupportFileService::deleteFile($self, $target, $fileid)) {
			return Response::create('1');
		} else {
			return Response::create('삭제실패했습니다.');
		}
	}

	public function fileDownload(Request $request, Application $app)
	{
		$self = UserSession::getSelfDto();

		$target = $request->get('target');
		$fileid = $request->get('fileid');
		if (!intval($fileid)) {
			throw new MsgException("invalid fileid");
		}

		return SupportFileService::downloadFile($self, $target, $fileid);
	}

	public function fileUpload(Request $request, Application $app)
	{
		$target = $request->get('target');
		$id = $request->get('id');
		$column_key = $request->get('column_key');

		if (!intval($id)) {
			throw new MsgException("invalid paymentid");
		}
		/**
		 * @var $file UploadedFile
		 */
		$file = $request->files->get('files')[0];

		if (SupportFileService::addFiles($target, $id, $column_key, $file)) {
			return JsonResponse::create('success');
		} else {
			return JsonResponse::create('file upload failed', 500);
		}
	}

	private function excelPostworkHeader($csv_header, $target)
	{
		if ($target == SupportPolicy::TYPE_BUSINESS_CARD) {
			$csv_header = array_merge($csv_header, [
				'이름 - 출력용 시작',
				'영문명',
				'부서명',
				'직급(한글)',
				'직급(영문)',
				'MOBILE',
				'E-MAIL',
				'PHONE(내선)',
				'FAX',
				'주소',
				'수량',
				'제작예정일'
			]);
		}
		return $csv_header;
	}

	private function excelPostworkBody($csv_row, $target)
	{
		if ($target == SupportPolicy::TYPE_BUSINESS_CARD) {
			if ($csv_row['대상자'] == '직원') {
				$csv_row[] = $csv_row['대상자(직원)'];
			} else {
				$csv_row[] = $csv_row['대상자(현재 미입사)'];
			}
			$csv_row[] = $csv_row['영문명'];
			if ($csv_row['부서명'] == '기타') {
				$csv_row[] = $csv_row['부서명(기타)'];
			} else {
				$csv_row[] = $csv_row['부서명'];
			}
			$csv_row[] = $csv_row['직급(한글)'];
			$csv_row[] = $csv_row['직급(영문)'];
			$csv_row[] = $csv_row['MOBILE'];
			$csv_row[] = $csv_row['E-MAIL'];
			$csv_row[] = $csv_row['PHONE(내선)'];
			$csv_row[] = $csv_row['FAX'];
			$csv_row[] = $csv_row['주소'];
			if ($csv_row['수량'] == '기타 - 50매 단위') {
				$csv_row[] = $csv_row['수량(기타)'];
			} else {
				$csv_row[] = $csv_row['수량'];
			}
			$csv_row[] = $csv_row['제작(예정)일'];
		}
		return $csv_row;
	}

	private function getExplain($target)
	{
		//'업무환경 불편사항 문의',
		if ($target == SupportPolicy::TYPE_DEVICE) {
			return
'1. 사내 전산 H/W 장애문의
-업무용 PC 및 노트북 등 H/W 장애 문의

2. SW 설치문의
-업무용 SW 설치 필요 시 문의

3. 기타 장애문의
-사무환경 및 사무집기 장애 문의';

		//'경조 지원'
		} else if ($target == SupportPolicy::TYPE_FAMILY_EVENT) {
			return
'1. 공통
-화환 수령 정보 상세 기재
-증빙서류 업로드 필수

2. 거래처
-거래처 [화환 종류]는 ‘기타’ 선택 후 [화환 상세]에 기재 (ex. 조화)

3. 임직원
-적용 대상 : 수습직원을 포함한 정직원 (TA 의 경우 사망에 한해 유급휴가만 적용)

-경조사 발생시 절차
A. 본인 or 해당 부서장을 통한 경조사 등록
B. 긴급하게 발생하는 조사의 경우 비상 연락망에 따라 연락 주시면, 선 경조규정 적용 후 등록 가능
C. 비상연락망 : 본인 -> 팀장 -> 인사팀 -> 대표이사

-경조휴가일 계산 : 휴일포함 (경조사 발생일 기준, 결혼에 한해 평일 기준 5일 적용)';

		//'명함 신청'
		} else if ($target == SupportPolicy::TYPE_BUSINESS_CARD) {
			return
'1. 공통
-매월 말일 제작 (불가피하게 급한 건만 제작(예정)일 설정)
-필요한 정보만 입력하고 나머지 공란으로 둠

2. 항목 설명
-대상자
A. 직원 : 재직 중인 직원
B. 현재 미입사 : 입사 예정인 직원

-영문명 : 이름, 성의 각 첫 글자만 대문자로 입력 (ex.Gildong Hong)
-직급(한글/영문) : 필요한 경우 기재
-PHONE(내선) : 내선번호 있는 경우 기재';

		//'구매 요청'
		} else if ($target == SupportPolicy::TYPE_DEPOT) {
			return
'1. 업무 상 필요한 자산 및 비품 구매 요청
2. 수령희망일은 배송기간 감안하여 설정';

		//'상품권 제작'
		} else if ($target == SupportPolicy::TYPE_GIFT_CARD) {
			return
'1. 권종, 유효기간, 수량에 맞는 난수번호 파일 업로드
2. 권종, 유효기간에 맞는 이미지 파일 업로드
3. 기본으로 상품권 1장당 봉투 1장 제작. 변경 필요 시 비고에 기재';
		}
	}
}
