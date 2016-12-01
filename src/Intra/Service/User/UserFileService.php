<?php

namespace Intra\Service\User;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Intra\Model\LightFileModel;

class UserFileService
{
	public static function addImage($file)
	{
		$self = UserSession::getSelfDto();

		$file_model = new LightFileModel('user_img');
		$dest = $file_model->getUploadableLocation($self->uid);
		if ($file->move(dirname($dest), basename($dest))) {
			return true;
		}
	}

	public static function getImage($key)
	{
		$file_model = new LightFileModel('user_img');
		$dest = $file_model->getUploadableLocation($key);
		if (is_file($dest)) {
			$binary_file_response = new BinaryFileResponse($dest, 200);
			return $binary_file_response;
		}
		return new Response('file not exist', 404);
	}
}
