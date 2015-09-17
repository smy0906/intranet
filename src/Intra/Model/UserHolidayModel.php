<?php

namespace Intra\Model;

use Intra\Service\IntraDb;
use Intra\Service\User;

class UserHolidayModel
{
	public static $const = array(
		'types' => array('연차', '오전반차', '오후반차', '공가', '경조', '대체휴가', '무급휴가', '무급오전반차', '무급오후반차', 'PWT'),
		'memos' => array('개인용무', '병원진료', '예비군훈련', '경조사', '기타')
	);

	public function __construct()
	{
		$this->db = IntraDb::getGnfDb();
	}

	public function getUsedCost(User $user, $begin, $end)
	{
		$where = array('uid' => $user->uid, 'hidden' => 0, 'date' => sqlBetween($begin, $end));
		return $this->db->sqlData('select sum(cost) from holidays where ?', sqlWhere($where));
	}

	/**
	 * @param User $user
	 * @param $begin
	 * @param $end
	 * @return HolidayRaw[]
	 */
	public function getHolidaysByUserYearly($user, $begin, $end)
	{
		if ($user == null) {
			$where = array(
				'hidden' => 0,
				'date' => sqlBetween($begin, $end),
			);
		} else {
			$where = array(
				'uid' => $user->uid,
				'hidden' => 0,
				'date' => sqlBetween($begin, $end),
			);
		}
		return $this->db->sqlObjects('select * from holidays where ? order by uid asc, date asc', sqlWhere($where));
	}

	public function add(HolidayRaw $holidayRaw)
	{
		$holiday = get_object_vars($holidayRaw);
		foreach ($holiday as $k => $v) {
			if (is_null($v)) {
				unset($holiday[$k]);
			}
		}

		$this->db->sqlInsert('holidays', $holiday);
		return $this->db->insert_id();
	}

	public function hide($holidayid, $uid)
	{
		$update = array('hidden' => 1);
		return $this->db->sqlUpdate('holidays', $update, compact('holidayid', 'uid'));
	}

	public function edit($holidayid, $uid, $key, $value)
	{
		$update = array($key => $value);
		$where = array(
			'holidayid' => $holidayid,
			'uid' => $uid
		);
		$this->db->sqlUpdate('holidays', $update, $where);
	}

	/**
	 * @param $holidayid
	 * @param $uid
	 * @return HolidayRaw
	 */

	public function get($holidayid, $uid)
	{
		return $this->gets(array($holidayid), $uid);
	}

	/**
	 * @param $holidayids
	 * @param $uid
	 * @return HolidayRaw
	 */

	public function gets(array $holidayids, $uid)
	{
		$where = array('holidayid' => $holidayids, 'uid' => $uid);
		return $this->db->sqlObjects('select * from holidays where ? order by date asc', sqlWhere($where));
	}

	public function isDuplicate($date, $uid)
	{
		$date = date('Y-m-d', strtotime($date));
		$where = array(
			'date' => $date,
			'uid' => $uid,
			'hidden' => 0
		);
		return $this->db->sqlCount('holidays', $where);
	}

	public function isDuplicateInDateRangeByType($this_month, $next_month, $type, $uid)
	{
		$where = array(
			'date' => sqlRange($this_month, $next_month),
			'type' => $type,
			'uid' => $uid,
			'hidden' => 0
		);
		return $this->db->sqlCount('holidays', $where);
	}
}
