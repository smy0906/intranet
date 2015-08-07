<?php
namespace Intra\Service;

use Intra\Lib\Response\CsvResponse;
use Intra\Model\UserFactory;
use Intra\Model\UserPaymentModel;
use Mailgun\Mailgun;

class UserPayment
{
	private $const;
	private $user_payment_model;
	/**
	 * @var User
	 */
	private $user;

	public static function parseMonth($month)
	{
		if ($month == null) {
			$month = date('Y-m');
		} else {
			$month = date('Y-m', strtotime($month));
		}
		return $month;
	}

	public function __construct(User $user)
	{
		$this->user = $user;

		$this->user_payment_model = new UserPaymentModel();

		$const = array();
		$const['teams'] = array(
			'스토어팀',
			'앱팀',
			'플랫폼팀',
			'UX팀',
			'일반팀',
			'만화팀',
			'로맨스팀',
			'판무팀',
			'마케팅팀',
			'DA팀',
			'콘텐츠디자인팀',
			'리디샵팀',
			'CC/PQ팀',
			'CO팀',
			'콘텐츠개발팀',
			'사업전략팀',
			'공통'
		);
		$const['products'] = array('리디북스', '리디샵', '공통');
		$const['taxs'] = array('Y', 'N', 'N/A');
		$const['paytypes'] = array('현금', '법인카드', '연구비카드', '연구비계좌');
		$const['statuses'] = array('결제 대기중', '결제 완료');
		$const['categorys'] = array(
			'상품매입 (리디샵 판매용 상품 매입 비용)',
			'운반비 (택배, 퀵서비스 이용대금)',
			'잡급 (로맨스, 판무 가이드 알바 급여)',
			'복리후생비',
			'소모품비 (리디샵 판매를 위한 포장자재, 기타 소모품)',
			'자산(PC, 공사대금 등)',
			'도서구입 및 인쇄비 (쿠폰, 상품권, 명함등 인쇄, 도서구입비)',
			'저작권료 (콘텐츠 매절 (마케팅용X))',
			'광고선전비',
			'지급수수료',
			'저작권료 (마케팅용 콘텐츠 매절)',
			'콘텐츠 지원금',
		);
		if (in_array(UserSession::getSelf()->getName(), array('박주현', '설다인', '한재선'))) {
			$const['categorys'][] = '기타';
		}
		$const['pay_dates'] = array('선택해주세요', '7일', '10일', '25일', '월말일', '긴급');
		$this->const = $const;
	}

	function index($month)
	{
		$return = array();
		$return['user'] = UserSession::getSupereditUser()->getDbDto();
		$uid = $this->user->uid;

		$prevmonth = date('Y-m', strtotime('-1 month', strtotime($month)));
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month)));

		$return['month'] = $month;
		$return['prevmonth'] = $prevmonth;
		$return['nextmonth'] = $nextmonth;
		$return['todayMonth'] = date('Y-m');
		$return['todayDate'] = date('Y-m-d');

		$payments = $this->user_payment_model->getPayments($uid, $month);
		$return['payments'] = $this->filterPayements($payments);
		$return['queuedPayments'] = $this->user_payment_model->queuedPayments();
		$return['todayQueuedCount'] = $this->user_payment_model->todayQueuedCount();
		$return['todayQueuedCost'] = $this->user_payment_model->todayQueuedCost();
		$return['currentUid'] = $this->user->uid;

		if ($this->isSuperAdmin()) {
			$return['isSuperAdmin'] = 1;
			$return['editable'] |= 1;
		}

		$return['allCurrentUsers'] = UserFactory::getAvailableUsers();
		$return['allUsers'] = UserFactory::getAllUsers();

		$return['const'] = $this->const;

		return $return;
	}

	function add($request_args)
	{
		$request_args = $this->filterAdd($request_args);
		$this->assertAdd($request_args);

		$insert_id = $this->user_payment_model->add($request_args);
		if (!$insert_id) {
			throw new \Exception('자료추가 실패했습니다');
		}

		$this->sendMail('결제요청', $insert_id);

		return 1;
	}

	function sendExcelResposeAndExit($month)
	{
		if (!$this->isSuperAdmin()) {
			return '권한이 없습니다';
		}
		$month = date('Y/m/1', strtotime($month));

		$payments = $this->user_payment_model->getAllPayments($month);
		//header
		$csvs = array();
		{
			$arr = array(
				'요청일',
				'요청자',
				'승인자',
				'귀속월',
				'귀속부서',
				'프로덕트',
				'분류',
				'상세내역',
				'업체명',
				'입금은행',
				'입금계좌번호',
				'예금주',
				'입금금액',
				'결제예정일',
				'세금계산서수취여부',
				'비고',
				'결제수단',
				'상태'
			);
			$csvs[] = $arr;
		}
		foreach ($payments as $payment) {
			$arr = array(
				$payment['request_date'],
				$payment['name'],
				Users::getByUid($payment['manager_uid'])->getName(),
				$payment['month'],
				$payment['team'],
				$payment['product'],
				$payment['category'],
				$payment['desc'],
				$payment['company_name'],
				$payment['bank'],
				'"' . $payment['bank_account'] . '"',
				$payment['bank_account_owner'],
				$payment['price'],
				$payment['pay_date'],
				$payment['tax'],
				$payment['note'],
				$payment['paytype'],
				$payment['status'],
			);
			$csvs[] = $arr;
		}

		$csvresponse = new CsvResponse($csvs);
		$csvresponse->send();
		exit;
	}

	function getConstValueByKey($key)
	{
		if ($key == 'manager_uid') {
			$ret = array();
			foreach (UserFactory::getAvailableUsers() as $user) {
				$ret[$user['uid']] = $user['name'];
			}
			return json_encode($ret);
		}
		$plural_key = $this->getPluralKey($key);
		if (!is_array($this->const[$plural_key])) {
			return null;
		}
		$ret = array();
		foreach ($this->const[$plural_key] as $v) {
			$ret[$v] = $v;
		}
		return json_encode($ret);
	}

	/**
	 * @param $key
	 * @return string
	 */
	private function getPluralKey($key)
	{
		if (substr($key, -1) == 's') {
			return $key . 'es';
		} else {
			return $key . 's';
		}
	}

	public function del($paymentid)
	{
		$uid = $this->user->uid;
		$res = $this->user_payment_model->del($paymentid, $uid);
		if ($res) {
			return 1;
		}
		return '삭제가 실패했습니다!';
	}

	function edit($paymentid, $key, $new_value)
	{
		$uid = $this->user->uid;

		$old_value = $this->user_payment_model->getValueByKey($paymentid, $uid, $key);
		if (!$this->assertEdit($key, $old_value, $new_value)) {
			return $old_value;
		}

		$this->user_payment_model->update($paymentid, $uid, $key, $new_value);
		$updated_value = $this->user_payment_model->getValueByKey($paymentid, $uid, $key);

		if ($key == 'status') {
			if ($updated_value == '결제 완료') {
				$type = '결제완료';
				$this->sendMail($type, $paymentid);
			}
		} elseif ($key == 'price') {
			return number_format($updated_value) . ' 원';
		} elseif ($key == 'manager_uid') {
			$user = Users::getByUid($updated_value);
			if ($user) {
				return $user->getName();
			}
			return 'error';
		}
		return $updated_value;
	}

	public function get_pay_date_by_str($pay_type_str)
	{
		if ($pay_type_str == '7일' || $pay_type_str == '10일' || $pay_type_str == '25일') {
			$dest_day = preg_replace('/\D/', '', $pay_type_str);
			$cur_date = time();
			while (date('d', $cur_date) != $dest_day) {
				$cur_date = strtotime('next day', $cur_date);
			}
			return date('Y-m-d', $cur_date);
		}
		if ($pay_type_str == '월말일') {
			$cur_date = strtotime('last day of this month');
			return date('Y-m-d', $cur_date);
		}
		return '';
	}

	/**
	 * @param $request_args
	 * @throws \Exception
	 */
	private function assertAdd($request_args)
	{
		if (!strtotime($request_args['month'] . '-1')) {
			throw new \Exception('귀속월을 다시 입력해주세요');
		}
		if (!strtotime($request_args['pay_date'])) {
			throw new \Exception('결제(예정)일을 다시 입력해주세요');
		}
	}

	private function assertEdit($key, $old_value, $new_value)
	{
		if ($key == 'date') {
			//날짜를 변경할때 다른 월로는 변경불가
			$month_new = date('Ym', strtotime($new_value));
			$month_old = date('Ym', strtotime($old_value));
			if ($month_new != $month_old) {
				return false;
			}
		}

		if (!$this->isSuperAdmin()) {
			return false;
		}
		return true;
	}

	private function sendMail($type, $insert_id)
	{
		$row = $this->user_payment_model->getPayment($insert_id);
		list($title, $text, $receivers) = $this->getMailContents($type, $row);
		$this->sendMailRaw($receivers, $title, $text);
	}

	/**
	 * @param $receivers
	 * @param $title
	 * @param $text
	 */
	private function sendMailRaw($receivers, $title, $text)
	{
		$receivers[] = '***REMOVED***';
		$receivers[] = '***REMOVED***';
		$receivers[] = '***REMOVED***';

		$mg = new Mailgun("***REMOVED***");
		$domain = "ridibooks.com";
		$mg->sendMessage(
			$domain,
			array(
				'from' => 'noreply@ridibooks.com',
				'to' => implode(', ', $receivers),
				'subject' => $title,
				'text' => $text
			)
		);
	}

	/**
	 * @param $payments
	 * @return mixed
	 */
	private function filterPayements($payments)
	{
		foreach ($payments as $k => $payment) {
			$payment['manager_name'] = Users::getByUid($payment['manager_uid'])->getName();
			$payments[$k] = $payment;
		}
		return $payments;
	}

	/**
	 * @param $type
	 * @param $row
	 * @return array
	 */
	private function getMailContents($type, $row)
	{
		$name = Users::getByUid($row['uid'])->getName();
		$title = "[{$type}][{$row['team']}][{$row['month']}] {$name}님의 요청, {$row['category']}";
		$text = "요청일 : {$row['request_date']}
요청자 : {$name}
분류 : {$row['team']} / {$row['product']}
내용 : {$row['category']}
상세내용 : {$row['desc']}
금액 : {$row['price']}
결제예정일 : {$row['pay_date']}";
		$receivers = array(
			Users::getByUid($row['uid'])->getEmail(),
			Users::getByUid($row['manager_uid'])->getName(),
		);
		return array($title, $text, $receivers);
	}

	/**
	 * @return bool
	 */
	private function isSuperAdmin()
	{
		return UserSession::getSelf()->isSuperAdmin();
	}

	/**
	 * @param $request_args
	 * @return mixed
	 */
	private function filterAdd($request_args)
	{
		$request_args['uid'] = $this->user->uid;
		$request_args['request_date'] = date('Y-m-d');
		$request_args['month'] = preg_replace('/\D/', '/', trim($request_args['month']));
		$request_args['month'] = date('Y-m', strtotime($request_args['month'] . '/1'));
		$request_args['pay_date'] = preg_replace('/\D/', '-', trim($request_args['pay_date']));
		return $request_args;
	}
}
