<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-06-03
 * Time: 오후 9:07
 */

namespace Intra\Service\Payment;


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
}
