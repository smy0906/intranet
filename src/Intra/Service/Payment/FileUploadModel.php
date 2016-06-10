<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-06-03
 * Time: 오전 12:30
 */

namespace Intra\Service\Payment;


use Intra\Core\BaseModel;

class FileUploadModel extends BaseModel
{
	/**
	 * @param $file_upload_dto FileUploadDto
	 * @return null
	 */
	public static function insert($file_upload_dto)
	{
		$dict = $file_upload_dto->exportDatabaseInsert();
		return self::getDb()->sqlInsert('files', $dict);
	}

	public static function getAlreadyRegistedCount($group, $key)
	{
		return self::getDb()->sqlCount('files', ['group' => $group, 'key' => $key]);
	}

	public static function getDictsByGroupAndKeys($group, $keys)
	{
		return self::getDb()->sqlDicts('select * from files where ?', sqlWhere(['group' => $group, 'key' => $keys]));
	}

	public static function getDictByPk($id)
	{
		return self::getDb()->sqlDict('select * from files where ?', sqlWhere(['id' => $id]));
	}
}
