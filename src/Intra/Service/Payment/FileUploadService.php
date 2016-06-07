<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-06-03
 * Time: 오전 12:13
 */

namespace Intra\Service\Payment;


use Intra\Model\LightFileModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
	private $group;
	private $light_file_model;

	/**
	 * FileUploadService constructor.
	 * @param $group
	 */
	public function __construct($group)
	{
		$this->group = $group;
		$this->light_file_model = new LightFileModel('__file_upload_' . $group);
	}

	/**
	 * @param $payment_id
	 * @param $file UploadedFile
	 * @return UploadedFile|false
	 */
	public function upload($payment_id, $file)
	{
		$return = false;
		FileUploadModel::transactional(
			function () use (&$return, $payment_id, $file) {
				$count = FileUploadModel::getAlreadyRegistedCount($this->group, $payment_id);
				$file_upload_dto = FileUploadDto::importFromUploadReqeust($file, $this->group, $payment_id, $count + 1);
				$dest = $this->light_file_model->getUploadableLocation($file_upload_dto->location);
				if ($file->move(dirname($dest), basename($dest))) {
					if (FileUploadModel::insert($file_upload_dto)) {
						$return = $file_upload_dto;
					}
				};
			}
		);
		return $return;
	}
}
