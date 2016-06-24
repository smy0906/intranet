<?php

namespace Intra\Model;

use Intra\Core\BaseModel;
use Intra\Service\IntraDb;

class PaymentModel extends BaseModel
{
	private $db;

	public function __construct()
	{
		$this->db = IntraDb::getGnfDb();
	}

	public function getPayments($uid, $month)
	{
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month)));

		$table = [
			'payments.uid' => 'users.uid'
		];
		$where = [
			'payments.uid' => $uid,
			sqlOr(
				['status' => ['결제 대기중']],
				['request_date' => sqlRange($month . '-1', $nextmonth . '-1')]
			)
		];

		return $this->db->sqlDicts(
			'select payments.*, users.name from ? where ? order by `status`, `request_date` asc, paymentid asc',
			sqlLeftJoin($table),
			sqlWhere($where)
		);
	}

	public function queuedPayments()
	{
		$table = [
			'payments.uid' => 'users.uid'
		];
		$where = [
			'status' => ["결제 대기중"]
		];

		return $this->db->sqlDicts(
			'select payments.*, users.name from ? where ? order by `pay_date` asc, paymentid asc',
			sqlLeftJoin($table),
			sqlWhere($where)
		);
	}

	public function queuedPaymentsByManager($uid)
	{
		$table = [
			'payments.uid' => 'users.uid',
			'payments.paymentid' => [
				'payment_accept.paymentid',
				'payment_accept.user_type' => 'manager',
			],
		];
		$where = [
			'status' => ["결제 대기중"],
			'payments.manager_uid' => $uid,
			'payment_accept.paymentid' => null,
		];

		return $this->db->sqlDicts(
			'select payments.*, users.name from ? where ? order by `status`,`pay_date` asc, paymentid asc',
			sqlLeftJoin($table),
			sqlWhere($where)
		);
	}

	public function todayQueuedCost()
	{
		$where = $this->getTodayQueuedWhere();
		return number_format(
			$this->db->sqlData(
				'select sum(price) from payments where ? order by `pay_date` asc, paymentid asc',
				sqlWhere($where)
			)
		);
	}

	/**
	 * @return array
	 */
	private function getTodayQueuedWhere()
	{
		return [
			'status' => ["결제 대기중"],
			'pay_date' => sqlRange(
				date('Y/m/d'),
				date('Y/m/d', strtotime('+1 day'))
			)
		];
	}

	public function todayQueuedCount()
	{
		$where = $this->getTodayQueuedWhere();

		return $this->db->sqlCount('payments', $where);
	}

	public function add($payment_insert)
	{
		$this->db->sqlInsert('payments', $payment_insert);
		return $this->db->insert_id();
	}

	public function getAllPayments($month)
	{
		$nextmonth = date('Y-m-1', strtotime('+1 month', strtotime($month)));

		$tables = [
			'payments.uid' => 'users.uid'
		];
		return $this->db->sqlDicts(
			'select users.name, payments.* from ? where `pay_date` between ? and ? order by pay_date asc, uid asc',
			sqlLeftJoin($tables),
			$month,
			$nextmonth
		);
	}

	public function del($paymentid)
	{
		return $this->db->sqlDelete('payments', compact('paymentid'));
	}

	public function update($paymentid, $key, $value)
	{
		$update = [$key => $value];
		$where = compact('paymentid');
		$this->db->sqlUpdate('payments', $update, $where);
	}

	public function getPayment($paymentid, $uid)
	{
		$where = [
			'paymentid' => $paymentid,
			sqlOr(
				['uid' => $uid],
				['manager_uid' => $uid]
			)

		];
		return $this->db->sqlDict('select * from payments where ?', sqlWhere($where));
	}

	public function getPaymentWithoutUid($paymentid)
	{
		$where = compact('paymentid');
		return $this->db->sqlDict('select * from payments where ?', sqlWhere($where));
	}

	public static function getPaydayIsAfter3days()
	{
		$on_3days_ago = date('Y/m/d 00:00:00', strtotime('-3 day'));
		$on_2days_ago = date('Y/m/d 00:00:00', strtotime('-2 day'));
		$where = [
			'pay_date' => sqlBetween($on_3days_ago, $on_2days_ago),
			'payments.status' => sqlNot('결제 완료'),
		];
		return self::getDb()->sqlDicts('select * from payments where ?', sqlWhere($where));
	}

	public static function getManagerNotYetAccepts()
	{
		$table = [
			'payments.paymentid' => ['payment_accept.paymentid', 'payment_accept.user_type' => 'manager']
		];
		$where = [
			'payment_accept.id' => null,
			'payments.status' => sqlNot('결제 완료'),
		];
		return self::getDb()->sqlDicts('select payments.* from ? where ?', sqlLeftJoin($table), sqlWhere($where));
	}

	public function todayQueued()
	{
		$table = [
			'payments.uid' => 'users.uid'
		];
		$where = $this->getTodayQueuedWhere();

		return $this->db->sqlDicts(
			'select payments.*, users.name from ? where ? order by `pay_date` asc, paymentid asc',
			sqlLeftJoin($table),
			sqlWhere($where)
		);
	}
}
