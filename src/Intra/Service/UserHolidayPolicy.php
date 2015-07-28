<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 13. 12. 24
 * Time: 오후 4:14
 */

namespace Intra\Service;

use DateTime;
use Intra\Model\UserHolidayModel;

class UserHolidayPolicy
{

	/**
	 * @var User
	 */
	private $user;
	private $user_holiday_model;

	function __construct(User $user)
	{
		$db = IntraDb::getGnfDb();
		$this->db = $db;

		$this->user = $user;
		$this->user_holiday_model = new UserHolidayModel();
	}

	/**
	 * @param $yearly
	 * @return int
	 */

	public function getAvailableCost($yearly)
	{
		$info = $this->getDetailInfomationByYearly($yearly);
		if ($yearly == 1) {
			//금년입사
			$fullCost = $info['worked_month_this_year'];
			return $fullCost;
		} elseif ($yearly == 2) {
			//작년입사(회계기준 조금 적용)
			$costByUpdatingYear = $info['base_holiday_count'];
			$costByThisYear = $info['worked_month_from_first_day_in_this_year'];
			$usedHolidayLastYear = $info['used_holiday_last_year'];
			return $costByUpdatingYear + $costByThisYear - $usedHolidayLastYear;
		} elseif ($yearly == 3) {
			//작년입사(회계기준 조금 적용)
			$costByUpdatingYear = 15;
			$usedHolidayLastYear = $info['used_holiday_last_year'];
			return $costByUpdatingYear - $usedHolidayLastYear;
		} else {
			//2014년시작 기점으로 완전한 회계기준 적용
			return min(25, 15 + floor(($yearly - 3) / 2));
		}
	}

	public function getDetailInfomationByYearly($yearly)
	{
		$ret = array();
		$onDate = $this->user->getOnDate();
		$yearlyBeginTimestamp = $this->getYearlyBeginTimestamp($yearly);
		$yearlyEndTimestamp = $this->getYearlyEndTimestamp($yearly);
		$ret['ondate'] = date('Y/m/d', strtotime($onDate));
		$ret['dateOfOndate'] = date('m/d', strtotime($onDate));
		if ($yearly == 1) {
			$from = date('Ymd', strtotime($onDate));
			$to = date('Ymd', min($yearlyEndTimestamp, time()));
			$monthDiff = floor(($to - $from) / 100);
			$ret['worked_month_this_year'] = max(0, $monthDiff);
		}
		if ($yearly == 2) {
			$nextYearOfOndateTimestamp = strtotime("+1 year", strtotime($onDate));
			//최대 12개아닌 11개까지 만 부여하기위해 '-1' 추가
			$endofPartTimestamp = min($nextYearOfOndateTimestamp - 1, time());

			$from = '00' . date('d', strtotime($onDate));
			$to = date('md', $endofPartTimestamp);
			$monthDiff = floor(($to - $from) / 100);
			$monthDiff %= 12;
			$ret['worked_month_from_first_day_in_this_year'] = max(0, $monthDiff);
		}
		if ($yearly == 2) {
			$beginOfPart = new DateTime($onDate);
			$endofPart = new DateTime();
			$endofPart->setTimestamp($yearlyBeginTimestamp);

			$diff = $beginOfPart->diff($endofPart);
			$costByUpdatingYearRaw = 15 * ($diff->days - 1) / 365;
			$costByUpdatingYear = floor($costByUpdatingYearRaw * 10) / 10;
			$ret['base_holiday_count'] = $costByUpdatingYear;
			$ret['worked_day_last_year'] = $diff->days - 1;
		}
		if ($yearly == 2) {
			$ret['used_holiday_last_year'] = floatval($this->getUsedCostByYearly($yearly - 1));
		}
		if ($yearly == 3) {
			$info = $this->getDetailInfomationByYearly(2);
			$last_year_usable_holiday_count = $this->getUsedCostByYearly(2) + $this->getUsedCostByYearly(1);
			$last_year_remain_cost = $info['base_holiday_count'];
			$exceeded_cost = $last_year_usable_holiday_count - $last_year_remain_cost;

			$ret['last_year_remain_cost'] = $last_year_remain_cost;
			$ret['last_year_usable_holiday_count'] = $last_year_usable_holiday_count;

			$ret['used_holiday_last_year'] = max(0, $exceeded_cost);
		}
		return $ret;
	}

	public function getYearlyBeginTimestamp($yearly)
	{
		$onDate = $this->user->getOnDate();
		$yearly -= 1;
		$targetDate = strtotime("+{$yearly} year", strtotime($onDate));

		$year = date('Y', $targetDate);
		$yearlyBeginTimestamp = strtotime($year . '/1/1');
		return max(strtotime($onDate), $yearlyBeginTimestamp);
	}

	public function getYearlyEndTimestamp($yearly)
	{
		$onDate = $this->user->getOnDate();
		$yearly -= 1;
		$targetDate = strtotime("+{$yearly} year", strtotime($onDate));

		$year = date('Y', $targetDate);
		$yearlyEndTimestamp = strtotime($year . '/12/31');
		return $yearlyEndTimestamp;
	}

	/**
	 * @param $yearly
	 * @return int
	 */

	public function getUsedCostByYearly($yearly)
	{
		$begin = date('Y/m/d', $this->getYearlyBeginTimestamp($yearly));
		$end = date('Y/m/d', $this->getYearlyEndTimestamp($yearly));
		return $this->user_holiday_model->getUsedCost($this->user, $begin, $end);
	}
}
