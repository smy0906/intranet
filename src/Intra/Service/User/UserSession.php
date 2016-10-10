<?php
namespace Intra\Service\User;

use Intra\Model\SessionModel;
use Intra\Model\UserModel;
use Intra\Service;

class UserSession
{
	/**
	 * @var SessionModel
	 */
	private static $session;

	public static function loginByAzure($id)
	{
		self::initStatic();

		UserJoinService::assertUserIdExist($id);
		$uid = UserModel::convertUidFromId($id);
		self::$session->set('users_uid', $uid);

		return true;
	}

	private static function initStatic()
	{
		self::$session = new SessionModel();
	}

	public static function logout()
	{
		self::initStatic();

		self::$session->set('users_uid', null);
	}

	/**
	 * @return UserDto
	 */
	public static function getSelfDto()
	{
		self::initStatic();

		if (!self::isLogined()) {
			return null;
		}
		if (self::$session->get('users_uid')) {
			$users_uid = self::$session->get('users_uid');
			return UserDtoFactory::createByUid($users_uid);
		}
		return null;
	}

	public static function isLogined()
	{
		self::initStatic();

		$users_uid = self::$session->get('users_uid');
		return intval($users_uid);
	}

	public static function isTa()
	{
		$user = self::getSelfDto();
		if ($user === null) {
			return false;
		}
		return UserPolicy::isTa($user);
	}

	public static function isPressManager()
	{
		$user = self::getSelfDto();
		if ($user === null) {
			return false;
		}

		return UserPolicy::isPressManager($user);
	}

	public static function isUserManager()
	{
		$user = self::getSelfDto();
		if ($user === null) {
			return false;
		}
		return UserPolicy::isUserManager($user);
	}
}
