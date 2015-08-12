<?php

namespace Intra\Model;

use Intra\Service\IntraDb;

class UserPaymentModel
{
	public function __construct()
	{
		$this->db = IntraDb::getGnfDb();
	}

	public function getPayments($uid, $month)
	{
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month)));

		$where = array(
			'payments.uid' => $uid,
			sqlOr(
				array('status' => '결제 대기중'),
				array('request_date' => sqlRange($month . '-1', $nextmonth . '-1'))
			)
		);

		#payments.uid = ? and (status = "결제 대기중" or (str_to_date(?, "%Y-%m-%d") <= `request_date` and `request_date` < str_to_date(?, "%Y-%m-%d")))
		return $this->db->sqlDicts(
			'select payments.*, users.name from payments left join users on payments.uid = users.uid where ? order by `request_date` asc, paymentid asc',
			sqlWhere($where)
		);
	}

	public function queuedPayments()
	{
		$where = array(
			'status' => "결제 대기중"
		);

		return $this->db->sqlDicts(
			'select payments.uid, pay_date, users.name from payments left join users on payments.uid = users.uid where ? order by `pay_date` asc, paymentid asc',
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
		return array(
			'status' => "결제 대기중",
			'pay_date' => sqlRange(
				date('Y/m/d'),
				date('Y/m/d', strtotime('+1 day'))
			)
		);
	}

	public function todayQueuedCount()
	{
		$where = $this->getTodayQueuedWhere();

		return $this->db->sqlCount('payments', $where);
	}

	public function add($request_args)
	{
		$this->db->sqlInsert('payments', $request_args);
		return $this->db->insert_id();
	}

	public function getAllPayments($month)
	{
		$nextmonth = date('Y-m-1', strtotime('+1 month', strtotime($month)));

		$tables = array(
			'payments.uid' => 'users.uid'
		);
		return $this->db->sqlDicts(
			'select users.name, payments.* from ? where `pay_date` between ? and ? order by pay_date asc, uid asc',
			sqlLeftJoin($tables),
			$month,
			$nextmonth
		);
	}

	public function del($paymentid, $uid)
	{
		return $this->db->sqlDelete('payments', compact('paymentid', 'uid'));
	}

	public function update($paymentid, $uid, $key, $value)
	{
		$update = array($key => $value);
		$where = compact('paymentid', 'uid');
		$this->db->sqlUpdate('payments', $update, $where);
	}

	public function getValueByKey($paymentid, $uid, $key)
	{
		$where = compact('paymentid', 'uid');
		return $this->db->sqlData('select ? from payments where ?', sqlColumn($key), sqlWhere($where));
	}

	public function getPayment($paymentid)
	{
		$where = compact('paymentid');
		return $this->db->sqlDict('select * from payments where ?', sqlWhere($where));
	}
}
