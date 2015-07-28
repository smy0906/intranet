<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-27
 * Time: 오후 12:16
 */

namespace Intra\Service;


use DateTime;
use Intra\Model\HolidayRaw;
use Intra\Model\UserFactory;
use Intra\Model\UserHolidayModel;
use Mailgun\Mailgun;

class UserHoliday
{
	/**
	 * @var User
	 */
	private $user;

	function __construct(User $user)
	{
		$this->user = $user;
		$this->user_holiday_policy = new UserHolidayPolicy($user);
		$this->user_holiday_model = new UserHolidayModel();
	}

	/**
	 * @param $yearly
	 * @return \Intra\Model\HolidayRaw[]
	 */

	public function getUserHolidays($yearly)
	{
		$begin = date('Y/m/d', $this->user_holiday_policy->getYearlyBeginTimestamp($yearly));
		$end = date('Y/m/d', $this->user_holiday_policy->getYearlyEndTimestamp($yearly));
		$holidays = $this->user_holiday_model->getHolidaysByUserYearly($this->user, $begin, $end);

		$this->filterHolidays($holidays);

		return $holidays;
	}

	/**
	 * @param $year
	 * @return \Intra\Model\HolidayRaw[]
	 */

	public function getHolidaysAllUsers($year)
	{
		$begin = date($year . '/1/1');
		$end = date(($year) . '/12/31');
		$holidays = $this->user_holiday_model->getHolidaysByUserYearly(null, $begin, $end);

		$this->filterHolidays($holidays);

		return $holidays;
	}

	/**
	 * @param $holidayid
	 * @return HolidayRaw
	 */
	private function getHoliday($holidayid)
	{
		$holidayRaw = $this->user_holiday_model->get($holidayid, $this->user->uid);
		$holidayRaws = array($holidayRaw);
		$this->filterHolidays($holidayRaws);
		return $holidayRaws[0];
	}

	/**
	 * @param $holidays
	 */
	private function filterHolidays($holidays)
	{
		foreach ($holidays as $holiday) {
			$holiday->uid_name = Users::getByUid($holiday->uid)->getName();
			$holiday->manager_uid_name = Users::getByUid($holiday->manager_uid)->getName();
			$holiday->keeper_uid_name = Users::getByUid($holiday->keeper_uid)->getName();
		}
	}

	public function edit($holidayid, $key, $value)
	{
		$this->assertEdit();

		$editable_keys = array('manager_uid', 'type', 'cost', 'keeper_uid', 'phone_emergency', 'memo');
		if (in_array($key, $editable_keys)) {
			$this->user_holiday_model->edit($holidayid, $this->user->uid, $key, $value);
		}

		$holidayRaw = $this->getHoliday($holidayid);
		return $holidayRaw->$key;
	}

	private function assertEdit()
	{
		$self = UserSession::getSelf();
		if ($self->uid == $this->user->uid) {
			return;
		}
		if (!$self->isSuperAdmin()) {
			throw new \Exception('권한이 없습니다.');
		}
	}

	public function del($holidayid)
	{
		$this->assertEdit();

		return $this->user_holiday_model->hide($holidayid, $this->user->uid);
	}

	/**
	 * @param HolidayRaw $holidayRaw
	 * @throws \Exception
	 */
	private function assertAdd($holidayRaw)
	{
		$dateTimestamp = strtotime($holidayRaw->date);
		if (!$dateTimestamp) {
			throw new \Exception("사용날짜를 다시 입력해주세요");
		}

		if ($dateTimestamp < $this->user_holiday_policy->getYearlyBeginTimestamp($holidayRaw->yearly)
			|| $this->user_holiday_policy->getYearlyEndTimestamp($holidayRaw->yearly) < $dateTimestamp
		) {
			//throw new Exception("연차가 맞지 않습니다. 사용년도를 확인해주세요");
		}

		if ($dateTimestamp < strtotime('-1 month')) {
			throw new \Exception("연차 사용날짜를 다시 입력해주세요. 이미 지난 시간입니다.");
		}

		if (in_array($holidayRaw->type, array('공가', '경조', '대체휴가', '무급휴가', 'PWT'))) {
			$holidayRaw->cost = 0;
		} elseif (in_array($holidayRaw->type, array('오전반차', '오후반차'))) {
			$holidayRaw->cost = 0.5;
		} elseif (in_array($holidayRaw->type, array('연차'))) {
			$int_cost = intval($holidayRaw->cost);
			if ($int_cost != $holidayRaw->cost || $int_cost <= 0) {
				throw new \Exception('연차는 자연수로만 입력가능합니다');
			}
			$holidayRaw->cost = $int_cost;
		} else {
			throw new \Exception("연차종류를 다시 확인해주세요");
		}

		if ($this->isDuplicate($holidayRaw->date)) {
			throw new \Exception("날짜가 중복됩니다. 다시 입력해주세요");
		}

		if ($holidayRaw->type == 'PWT' && $this->isDuplicatePWT($holidayRaw->date)) {
			throw new \Exception("이미 이번달에는 PWT를 사용하셨습니다");
		}

		if (!preg_match('/\d{3}-?\d{3,4}-?\d{4}/', $holidayRaw->phone_emergency)) {
			throw new \Exception("비상시 연락처를 다시 입력해주세요");
		}
	}

	private function isDuplicate($date)
	{
		return $this->user_holiday_model->isDuplicate($date, $this->user->uid);
	}

	private function isDuplicatePWT($date)
	{
		$this_month = date('Y-m-1', strtotime($date));
		$next_month = date('Y-m-1', strtotime('next month', strtotime($date)));

		return $this->user_holiday_model->isDuplicateInDateRangeByType(
			$this_month,
			$next_month,
			'PWT',
			$this->user->uid
		);
	}

	/**
	 * @param HolidayRaw $holidayRaw
	 * @return int
	 */
	public function add($holidayRaw)
	{
		$holidayRaw = $this->filterAdd($holidayRaw);
		$this->assertAdd($holidayRaw);

		$holidayRaw->uid = $this->user->uid;
		return $this->user_holiday_model->add($holidayRaw);
	}

	public function sendNotification($holidayid, $type)
	{
		$holidayRaw = $this->user_holiday_model->get($holidayid, $this->user->uid);
		$title = $this->getMailTitle($holidayRaw, $type);
		$ret = $this->sendMailNotification($holidayRaw, $title);
		$this->sendSlackNotification($title);

		return $ret->http_response_code == 200;
	}

	/**
	 * @param $holidayRaw HolidayRaw
	 * @param $type
	 * @return string
	 */
	private function getMailTitle($holidayRaw, $type)
	{
		return "[$type][{$holidayRaw->type}][{$holidayRaw->date}] {$this->user->getName()}님의 {$holidayRaw->cost}일 휴가사용신청";
	}

	/**
	 * @param $holidayRaw
	 * @param $title
	 * @return \stdClass
	 * @throws \Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters
	 */
	private function sendMailNotification($holidayRaw, $title)
	{
		$emails = $this->getMailReceivers($holidayRaw);
		$contents = $this->getMailContents($holidayRaw);

		$mg = new Mailgun("***REMOVED***");
		$domain = "ridibooks.com";
		$ret = $mg->sendMessage(
			$domain,
			array(
				'from' => 'noreply@ridibooks.com',
				'to' => implode(', ', $emails),
				'subject' => $title,
				'text' => $contents
			)
		);
		return $ret;
	}

	/**
	 * @param HolidayRaw $holidayRaw
	 * @return array
	 */
	private function getMailReceivers($holidayRaw)
	{
		$uids = array($holidayRaw->uid, $holidayRaw->manager_uid, $holidayRaw->keeper_uid);
		$uids = array_filter(array_unique($uids));
		$users = new Users;
		$user_list = $users->getUsersByUids($uids);

		$emails = array();
		foreach ($user_list as $user) {
			$emails[] = $user->getId() . '@ridi.com';
		}
		$emails[] = '***REMOVED***';
		$emails[] = '***REMOVED***';

		return array_unique(array_filter($emails));
	}

	/**
	 * @param HolidayRaw $holidayRaw
	 * @return string
	 * @throws \Exception
	 */
	private function getMailContents($holidayRaw)
	{
		$keeper = UserFactory::getByUid($holidayRaw->keeper_uid);
		if ($keeper === null) {
			throw new \Exception('$keeper === null');
		}
		$request_date = date('Y-m-d');

		$text = "요청일 : {$request_date}
요청자 : {$this->user->getName()}
종류 : {$holidayRaw->type}
사용연차 : {$holidayRaw->cost}
사용날짜 : {$holidayRaw->date}
업무인수인계자 : {$keeper->getName()}
비상시연락처 : {$holidayRaw->phone_emergency}
비고 : {$holidayRaw->memo}
";
		return $text;
	}

	private function sendSlackNotification($message)
	{
		$data = "payload=" . json_encode(array("text" => $message));

		// You can get your webhook endpoint from your Slack settings
		$ch = curl_init("https://hooks.slack.com/services/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");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	/**
	 * @return int
	 */

	public function getYearly()
	{
		$onDate = $this->user->getOnDate();
		$from = new DateTime($onDate);
		$to = new DateTime("first day of this year");

		$diff = $from->diff($to);

		//금년 입사
		if ($diff->invert) {
			return 1;
		}
		$yearly = $diff->y + 2;
		return $yearly;
	}


	public function getYearByYearly($yearly)
	{
		$onDate = $this->user->getOnDate();
		$onDateTimestamp = strtotime($onDate);
		$joinYear = date('Y', $onDateTimestamp);
		return $joinYear - 1 + $yearly;
	}

	/**
	 * @param $holidayRaw
	 */
	private function filterAdd($holidayRaw)
	{
		if ($holidayRaw->type == '연차' && strlen(trim($holidayRaw->cost)) == 0) {
			$holidayRaw->cost = 1;
		}
		return $holidayRaw;
	}
}
