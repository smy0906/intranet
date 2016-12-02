<?php

namespace Intra\Service\User;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Intra\Model\LightFileModel;

/**
 * Class UserEditService
 * @package Intra\Service\User
 */
class UserEditService
{
	/**
	 * @param $file
	 * @return bool
	 */
	public static function addImage($file)
	{
		$self = UserSession::getSelfDto();

		$file_model = new LightFileModel('user_img');
		$dest = $file_model->getUploadableLocation($self->uid);
		if ($file->move(dirname($dest), basename($dest))) {
			return true;
		}
	}

	/**
	 * @param $key
	 * @return BinaryFileResponse|Response
	 */
	public static function getImage($key)
	{
		$file_model = new LightFileModel('user_img');
		$dest = $file_model->getUploadableLocation($key);
		if (is_file($dest)) {
			return new BinaryFileResponse($dest, 200);
		}
		return new Response('file not exist', 404);
	}
}
