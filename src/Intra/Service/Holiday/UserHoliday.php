<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-27
 * Time: 오후 12:16
 */

namespace Intra\Service\Holiday;

use Intra\Model\HolidayModel;
use Intra\Service\User\UserDto;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

class UserHoliday
{
	private $cost_unselectable_type = ['PWT', '오전반차', '오후반차', '무급오전반차', '무급오후반차'];
	private $cost_zero_type = ['공가', '경조', '대체휴가', '무급휴가', 'PWT', '무급오전반차', '무급오후반차'];
	private $cost_half_type = ['오전반차', '오후반차'];
	private $cost_int_type = ['연차'];
	/**
	 * @var UserDto
	 */
	private $user;

	/**
	 * UserHoliday constructor.
	 * @param UserDto $user
	 */
	public function __construct(UserDto $user)
	{
		$this->user = $user;
		$this->user_holiday_policy = new UserHolidayPolicy($user);
		$this->user_holiday_model = new HolidayModel();
	}

	/**
	 * @param $yearly
	 * @return UserHolidayDto[]
	 */

	public function getUserHolidays($yearly)
	{
		$begin = date('Y/m/d', $this->user_holiday_policy->getYearlyBeginTimestamp($yearly));
		$end = date('Y/m/d', $this->user_holiday_policy->getYearlyEndTimestamp($yearly));
		$holidays = $this->user_holiday_model->getHolidaysByUserYearly($this->user, $begin, $end);

		self::filterHolidays($holidays);

		return $holidays;
	}

	/**
	 * @param $holidayid
	 * @return UserHolidayDto
	 */
	private function getHoliday($holidayid)
	{
		$holidayRaw = $this->user_holiday_model->get($holidayid, $this->user->uid);
		$holidayRaws = [$holidayRaw];
		self::filterHolidays($holidayRaws);
		return $holidayRaws[0];
	}

	/**
	 * @param $holidays
	 */
	public static function filterHolidays($holidays)
	{
		foreach ($holidays as $holiday) {
			$holiday->uid_name = self::getUserNameSafe($holiday->uid);
			$holiday->manager_uid_name = self::getUserNameSafe($holiday->manager_uid);
			$holiday->keeper_uid_name = self::getUserNameSafe($holiday->keeper_uid);
		}
	}

	public function edit($holidayid, $key, $value)
	{
		$this->assertEdit();

		$editable_keys = ['manager_uid', 'type', 'cost', 'keeper_uid', 'phone_emergency', 'memo'];
		if (in_array($key, $editable_keys)) {
			$this->user_holiday_model->edit($holidayid, $this->user->uid, $key, $value);
		}

		$holidayRaw = $this->getHoliday($holidayid);
		return $holidayRaw->$key;
	}

	private function assertEdit()
	{
		$self = UserSession::getSelfDto();
		if ($self->uid == $this->user->uid) {
			return;
		}
		if (!UserPolicy::is_holiday_editable(UserSession::getSelfDto())) {
			throw new \Exception('권한이 없습니다.');
		}
	}

	public function del($holidayid)
	{
		$this->assertEdit();

		return $this->user_holiday_model->hide($holidayid, $this->user->uid);
	}

	/**
	 * @param UserHolidayDto $holidayRaw
	 * @throws \Exception
	 */
	private function assertAdd($holidayRaw)
	{
		$dateTimestamp = strtotime($holidayRaw->date);
		if (!$dateTimestamp || $dateTimestamp <= 0) {
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

		if (in_array($holidayRaw->type, $this->cost_zero_type)) {
			$holidayRaw->cost = 0;
		} elseif (in_array($holidayRaw->type, $this->cost_half_type)) {
			$holidayRaw->cost = 0.5;
		} elseif (in_array($holidayRaw->type, $this->cost_int_type)) {
			$int_cost = intval($holidayRaw->cost);
			if ($int_cost != $holidayRaw->cost || $int_cost <= 0) {
				throw new \Exception('연차는 자연수로만 입력가능합니다');
			}
			$holidayRaw->cost = $int_cost;
		} else {
			throw new \Exception("연차종류를 다시 확인해주세요");
		}

		if ($holidayRaw->cost > 0) {
			$remain_cost = $this->user_holiday_policy->getRemainCost($holidayRaw->yearly);
			if ($remain_cost < $holidayRaw->cost) {
				throw new \Exception("남아있는 연차가 없습니다. 무급휴가만 사용가능합니다." . $holidayRaw->yearly);
			}
		}

		if ($this->isDuplicate($holidayRaw->date)) {
			throw new \Exception("날짜가 중복됩니다. 다시 입력해주세요");
		}

		if ($holidayRaw->keeper_uid == 0) {
			throw new \Exception("업무인수인계자를 선택해주세요");
		}

		if ($holidayRaw->manager_uid == 0) {
			throw new \Exception("결재자를 선택해주세요");
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
	 * @param UserHolidayDto $holidayRaw
	 * @return int[]
	 */
	public function add($holidayRaw)
	{
		$holidayRaw = $this->filterAdd($holidayRaw);
		$this->assertAdd($holidayRaw);
		$holidayRaws = $this->convertToArrayToAdd($holidayRaw);

		$holiday_ids = [];
		foreach ($holidayRaws as $holidayRaw) {
			$holiday_id = $this->user_holiday_model->add($holidayRaw);
			if (!$holiday_id) {
				return false;
			}
			$holiday_ids[] = $holiday_id;
		}
		return $holiday_ids;
	}

	public function sendNotification(array $holidayids, $action_type)
	{
		$holiday_raws = $this->user_holiday_model->gets($holidayids, $this->user->uid);
		$user_holiday_notification = new UserHolidayNotification($this->user, $holiday_raws, $action_type);
		return $user_holiday_notification->sendNotification();
	}

	/**
	 * @param null $timestamp
	 * @return int
	 */

	public function getYearly($timestamp = null)
	{
		$fromDate = $this->user->on_date;
		if ($timestamp === null) {
			$timestamp = time();
		}
		$fromYear = date('Y', strtotime($fromDate));
		$toDate = date('Y', $timestamp);
		return max(0, $toDate - $fromYear);
	}


	public function getYearByYearly($yearly)
	{
		$onDate = $this->user->on_date;
		$onDateTimestamp = strtotime($onDate);
		$joinYear = date('Y', $onDateTimestamp);
		return $joinYear + $yearly;
	}

	/**
	 * @param $holidayRaw
	 */
	private function filterAdd($holidayRaw)
	{
		if (!in_array($holidayRaw->type, $this->cost_unselectable_type) && strlen(trim($holidayRaw->cost)) == 0) {
			$holidayRaw->cost = 1;
		}
		$holidayRaw->uid = $this->user->uid;
		return $holidayRaw;
	}

	/**
	 * @param $keeper_uid
	 * @return mixed
	 */
	public static function getUserNameSafe($keeper_uid)
	{
		return UserService::getNameByUidSafe($keeper_uid);
	}

	/**
	 * @param $holidayRaw UserHolidayDto
	 * @return UserHolidayDto[]
	 */
	private function convertToArrayToAdd($holidayRaw)
	{
		if ($holidayRaw->cost > 1) {
			$return = [];
			$cost = $holidayRaw->cost;
			$date = $holidayRaw->date;
			while ($cost > 0) {
				$holidayRaw->cost = 1;
				$holidayRaw->date = $date;
				$return[] = clone $holidayRaw;
				$cost--;
				$date = $this->getNextDateWhichIsNotWeekend($date);
			}
			return $return;
		} else {
			return [$holidayRaw];
		}
	}

	private function isWeekend($date)
	{
		return (date('N', strtotime($date)) >= 6);
	}

	private function getNextDateWhichIsNotWeekend($date)
	{
		$date = $this->getNextDate($date);
		while ($this->isWeekend($date)) {
			$date = $this->getNextDate($date);
		}
		return $date;
	}

	/**
	 * @param $date
	 * @return bool|string
	 */
	private function getNextDate($date)
	{
		$date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
		return $date;
	}
}
