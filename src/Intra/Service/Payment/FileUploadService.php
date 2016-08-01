<?php
namespace Intra\Service\Payment;

use Intra\Model\LightFileModel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

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
	 * @param $uid
	 * @param $payment_id
	 * @param $file UploadedFile
	 * @return false|UploadedFile
	 */
	public function upload($uid, $payment_id, $file)
	{
		$return = false;
		FileUploadModel::transactional(
			function () use (&$return, $uid, $payment_id, $file) {
				$count = FileUploadModel::getAlreadyRegistedCount($this->group, $payment_id);
				$file_upload_dto = FileUploadDto::importFromUploadReqeust(
					$uid, $file, $this->group, $payment_id, $count + 1
				);
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

	/**
	 * @param $file_upload_dto FileUploadDto
	 * @return BinaryFileResponse|Response
	 */
	public function getBinaryFileResponseWithDto($file_upload_dto)
	{
		$dest = $this->light_file_model->getUploadableLocation($file_upload_dto->location);
		if (is_file($dest)) {
			return new BinaryFileResponse($dest);
		}
		return new Response('file not exist', 404);
	}

	/**
	 * @param $file_upload_dto FileUploadDto
	 * @return bool
	 */
	public function remove($file_upload_dto)
	{
		return FileUploadModel::remove($file_upload_dto->id);
	}
}
