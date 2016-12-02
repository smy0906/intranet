<?php
namespace Intra\Service\User;

use Intra\Model\LightFileModel;
use Intra\Model\UserModel;

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
	public static function updateImageFile($file)
	{
		$self = UserSession::getSelfDto();

		$file_model = new LightFileModel('user_img');
		$dest = $file_model->getUploadableLocation($self->uid);
		if (!$file->move(dirname($dest), basename($dest))) {
			return null;
		}

		$dto = UserSession::getSelfDto();
		$uid = $dto->uid;
		return UserEditService::updateInfo($uid, 'image', '/users/' . $uid . '/image');
	}

	/**
	 * @param $key
	 * @return null|string
	 */
	public static function getImage($key)
	{
		$file_model = new LightFileModel('user_img');
		$dest = $file_model->getUploadableLocation($key);
		if (is_file($dest)) {
			return $dest;
		}
		return null;
	}

	/**
	 * @param $uid
	 * @param $key
	 * @param $value
	 * @return null|string
	 */
	public static function updateInfo($uid, $key, $value)
	{
		$self = UserSession::getSelfDto();
		if (!$self) {
			return null;
		}

		if ($self->uid != $uid
			&& !UserSession::isUserManager()) {
			return null;
		}

		UserModel::update($uid, [$key => $value]);

		$user_dto = UserDtoFactory::createByUid($uid);
		return $user_dto->$key;
	}
}
