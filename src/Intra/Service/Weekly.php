<?php

namespace Intra\Service;

use Exception;
use Intra\Model\lightFileModel;

class Weekly
{
	public function __construct()
	{
		date_default_timezone_set('Asia/Seoul');
	}

	public function assertPermission()
	{
		// free pass
		if ($_GET['pw'] == 'mu57u53') {
			return;
		}

		if (!Ridi::isRidiIP()) {
			throw new Exception('권한이 없습니다.');
		}

		// It's Monday only
		if (date('w') != 1) {
			throw new Exception('열람 가능한 요일이 아닙니다.');
		}
	}

	public function getContents()
	{
		$filebag = new lightFileModel('weekly');
		$filename = date("Ym") . '-' . floor((date('d') - 1) / 7 + 1) . ".html";
		if (!$filebag->isExist($filename)) {
			throw new Exception('내용이 준비되지 않았습니다.');
		}
		$html = file_get_contents($filebag->getLocation($filename));
		$html = str_replace(
			'#FF0000',
			'#888888',
			$html
		);
		return $html;
	}
}
