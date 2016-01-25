<?php

namespace Intra\Service;

use Intra\Lib\Response\CsvResponse;

class UserReceiptsStat
{
	public function download($month)
	{
		$db = IntraDb::getGnfDb();

		$month = date('Y-m', strtotime($month));
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month)));

		$tables = array(
			'receipts.uid' => 'users.uid'
		);
		$receipts = $db->sqlDicts(
			'select users.name, receipts.* from ? where str_to_date(?, "%Y-%m-%d") <= `date` and date < str_to_date(?, "%Y-%m-%d") order by uid asc, `date` asc, receiptid asc',
			sqlLeftJoin($tables),
			$month,
			$nextmonth
		);

		$csvs = array();
		//header
		{
			$arr = array('이름', '날짜', '상호', '금액', '적요', '분류', '지불방식', '용도');
			$csvs[] = $arr;
		}
		foreach ($receipts as $receipt) {
			$arr = array(
				$receipt['name'],
				$receipt['date'],
				$receipt['title'],
				$receipt['cost'],
				$receipt['note'],
				$receipt['type'],
				$receipt['payment'],
				$receipt['scope']
			);
			$csvs[] = $arr;
		}
		$csvresponse = new CsvResponse($csvs, 'download.' . $month);
		$csvresponse->send();
		exit;
	}

	public function downloadYear($month)
	{
		$db = IntraDb::getGnfDb();

		$year = date('Y', strtotime($month));

		$tables = array(
			'receipts.uid' => 'users.uid'
		);
		$receipts = $db->sqlDicts(
			'select SUBSTR(`date`, 1, 7 ) as yearmonth, users.name, title, scope, type, payment, sum(cost) as cost from ? where year(`date`) = ? group by yearmonth, users.name, scope, type, payment ',
			sqlLeftJoin($tables),
			$year
		);

		$csvs = array();
		//header
		{
			$arr = array('월', '이름', '상호', '금액', '적요', '분류', '지불방식', '용도');
			$csvs[] = $arr;
		}
		foreach ($receipts as $receipt) {
			$arr = array(
				$receipt['yearmonth'] . '월',
				$receipt['name'],
				$receipt['title'],
				$receipt['scope'],
				$receipt['type'],
				$receipt['payment'],
				$receipt['cost'],
				$receipt['scope'],
			);
			$csvs[] = $arr;
		}
		$csvresponse = new CsvResponse($csvs, 'downloadYear.' . $year);
		$csvresponse->send();
		exit;
	}
}
