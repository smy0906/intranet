<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-06-03
 * Time: 오후 9:07
 */

namespace Intra\Service\Payment;


use Intra\Core\MsgException;

class FileUploadDtoFactory
{

	public static function createFromDatabaseDicts($payment_files_dicts)
	{
		$return = [];
		if (is_array($payment_files_dicts)) {
			foreach ($payment_files_dicts as $payment_files_dict) {
				$return[] = FileUploadDto::importFromDatabaseDict($payment_files_dict);
			}
		}
		return $return;
	}

	public static function importDtoByPk($id)
	{
		$dict = FileUploadModel::getDictByPk($id);
		if (!$dict) {
			throw new MsgException("파일정보에 오류가 있습니다. (삭제된 파일일 수 있습니다)");
		}
		return FileUploadDto::importFromDatabaseDict($dict);
	}
}
