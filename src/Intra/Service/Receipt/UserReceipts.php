<?php

namespace Intra\Service\Receipt;

use Intra\Model\UserFactory;
use Intra\Service\IntraDb;
use Intra\Service\User\User;
use Intra\Service\User\UserSession;

class UserReceipts
{
	private $user;

	/**
	 * @param $user User
	 */
	public function __construct($user)
	{
		$this->user = $user;
	}

	public static function queryWeekend($month, $day)
	{
		$date = $month . '-' . $day;
		if (self::isWeekend($date)) {
			return '(<span style="color:red">주말</span>)';
		}
		return "(평일)";
	}

	private static function isWeekend($date)
	{
		return (date('N', strtotime($date)) >= 6);
	}


	public function index($month = null)
	{
		$db = IntraDb::getGnfDb();

		$return = array();

		$return['user'] = $this->user;
		$uid = $this->getSupereditUid();

		$prevmonth = date('Y-m', strtotime('-1 month', strtotime($month)));
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month)));

		$return['month'] = $month;
		$return['prevmonth'] = $prevmonth;
		$return['nextmonth'] = $nextmonth;
		$return['receipts'] = $db->sqlDicts(
			'select * from receipts where uid = ? and str_to_date(?, "%Y-%m-%d") <= `date` and `date` < str_to_date(?, "%Y-%m-%d") order by `date` asc, receiptid asc',
			$uid,
			$month,
			$nextmonth
		);


		//용도별 통계

		$tbls = $db->sqlDicts(
			'select scope, type, sum(cost) as cost, count(*) as count from receipts where uid = ? and str_to_date(?, "%Y-%m-%d") <= date and date < str_to_date(?, "%Y-%m-%d") group by scope, type order by scope, type',
			$uid,
			$month,
			$nextmonth
		);
		$sumByScope = $db->sqlDicts(
			'select scope, sum(cost) as cost, count(*) as count from receipts where uid = ? and str_to_date(?, "%Y-%m-%d") <= date and date < str_to_date(?, "%Y-%m-%d") group by scope order by scope',
			$uid,
			$month,
			$nextmonth
		);
		$sumByType = $db->sqlDicts(
			'select type, sum(cost) as cost, count(*) as count from receipts where uid = ? and str_to_date(?, "%Y-%m-%d") <= date and date < str_to_date(?, "%Y-%m-%d") group by type order by type',
			$uid,
			$month,
			$nextmonth
		);
		$sum = $db->sqlDict(
			'select sum(cost) as cost, count(*) as count from receipts where uid = ? and str_to_date(?, "%Y-%m-%d") <= date and date < str_to_date(?, "%Y-%m-%d")',
			$uid,
			$month,
			$nextmonth
		);


		$cols = array(
			'합계' => 1
		);
		foreach ($sumByScope as $tbl) {
			$cols[$tbl['scope']] = 1;
		}
		foreach ($cols as $k => $v) {
			if (!$v) {
				unset($cols[$k]);
			}
		}

		$rows = array(
			'저녁/휴일 식사비' => 0,
			'팀런치' => 0,
			'접대비' => 0,
			'야근교통비' => 0,
			'업무차 식음료비' => 0,
			'업무차 교통비' => 0,
			'회식비' => 0,
			'땀친 지원비' => 0,
			'기타' => 0,
			'합계' => 1
		);
		foreach ($sumByType as $tbl) {
			$rows[$tbl['type']] = 1;
		}
		foreach ($rows as $k => $v) {
			if (!$v) {
				unset($rows[$k]);
			}
		}

		$costs = array();
		foreach ($rows as $row => $null) {
			foreach ($cols as $col => $null2) {
				$costs[$row][$col] = array('cost' => 0, 'count' => 0);
			}
		}
		foreach ($tbls as $tbl) {
			$costs[$tbl['type']][$tbl['scope']] = $tbl;
		}
		foreach ($sumByScope as $tbl) {
			$costs['합계'][$tbl['scope']] = $tbl;
		}
		foreach ($sumByType as $tbl) {
			$costs[$tbl['type']]['합계'] = $tbl;
		}
		$costs['합계']['합계'] = $sum;

		$return['cols'] = $cols;
		$return['costs'] = $costs;

		//지불방식별 통계

		$return['paymentCosts'] = $db->sqlDicts(
			'select payment, sum(cost) as cost from receipts where uid = ? and str_to_date(?, "%Y-%m-%d") <= date and date < str_to_date(?, "%Y-%m-%d") group by payment order by payment, type',
			$uid,
			$month,
			$nextmonth
		);
		$sum = 0;
		foreach ($return['paymentCosts'] as $cost) {
			$sum += $cost['cost'];
		}
		$return['paymentCosts'][] = array('payment' => '합계', 'cost' => $sum);


		$return['currentUid'] = $this->getSupereditUid();
		$return['editable'] = (UserReceipts::parseMonth() <= $month);
		if (UserSession::getSelf()->isSuperAdmin()) {
			$return['isSuperAdmin'] = 1;
			$return['editable'] |= 1;
		}

		$return['allCurrentUsers'] = UserFactory::getAvailableUsers();
		$return['allUsers'] = UserFactory::getAllUsers();

		return $return;
	}

	private function getSupereditUid()
	{
		return $this->user->uid;
	}

	public static function parseMonth($month = null)
	{
		if ($month == null) {
			$cur_month = date('Y-m', strtotime('-15 day'));
			$month = $cur_month;
		} else {
			$month = date('Y-m', strtotime($month));
		}
		return $month;
	}

	public function add($month, $day, $title, $scope, $type, $cost, $payment, $note)
	{
		$db = IntraDb::getGnfDb();

		$row = array(
			'title' => $title,
			'scope' => $scope,
			'type' => $type,
			'cost' => $cost,
			'payment' => $payment,
			'note' => $note
		);
		$row['uid'] = $this->getSupereditUid();
		$row['date'] = date('Y-m-d', strtotime($month . '-' . $day));
		if ($row['note'] == '저녁식사비' && self::isWeekend($row['date'])) {
			$row['note'] = '휴일식사비';
		}

		$this->assertAdd($row);

		$res = $db->sqlInsert('receipts', $row);

		if ($res) {
			return 1;
		}
		return '자료를 추가할 수 없습니다. 다시 확인해 주세요';
	}

	private function assertAdd($row)
	{
		$working_month = self::parseMonth();
		$timestamp_working_month = strtotime($working_month . '-1');

		if ($row['type'] == '저녁/휴일 식사비' && $row['cost'] > 8000) {
			throw new \Exception('"저녁/휴일 식사비"는 8000원 이하이어야합니다');
		}
		$timestamp_input_date = strtotime($row['date']);
		$timestamp_month = strtotime('first day of this month', $timestamp_working_month);
		if ($timestamp_input_date < $timestamp_month) {
			throw new \Exception('날짜를 확인해주세요');
		}

		if ($row['payment'] == null) {
			throw new \Exception('지불방식을 선택해주세요');
		}
	}

	public function del($receiptid)
	{
		$db = IntraDb::getGnfDb();

		$uid = $this->getSupereditUid();
		$res = $db->sqlDelete('receipts', compact('receiptid', 'uid'));
		if ($res) {
			return 1;
		}
		return '삭제가 실패했습니다!';
	}

	public function edit($receiptid, $key, $value)
	{
		$db = IntraDb::getGnfDb();

		$uid = $this->getSupereditUid();

		$update = array($key => $value);
		$where = compact('uid', 'receiptid');

		$old_value = $db->sqlData('select ? from receipts where ?', sqlColumn($key), sqlWhere($where));
		if ($key == 'date') {
			$month_new = date('Ym', strtotime($value));
			$month_old = date('Ym', strtotime($old_value));
			if ($month_new != $month_old) {
				return $old_value;
			}
		}

		$db->sqlUpdate('receipts', $update, compact('receiptid', 'uid'));
		$new_value = $db->sqlData('select ? from receipts where ?', sqlColumn($key), sqlWhere($where));
		if ($key == 'cost') {
			return number_format($new_value) . ' 원';
		}
		return $new_value;
	}
}
