<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 1. 7
 * Time: 오후 3:50
 */

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

		UserService::assertUserIdExist($id);
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
	 * "현재 편집중인 유저" or "본인"
	 */
	public static function getSupereditUserDto()
	{
		self::initStatic();

		$super_edit_uid = self::$session->get('super_edit_uid');
		$self = self::getSelfDto();

		if ($super_edit_uid && $self && $self->is_admin) {
			return UserService::getDtobyUid($super_edit_uid);
		} else {
			return $self;
		}
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
			return UserService::getDtobyUid($users_uid);
		}
		return null;
	}

	public static function isLogined()
	{
		self::initStatic();

		$users_uid = self::$session->get('users_uid');
		return intval($users_uid);
	}

	public static function setSupereditUser($uid)
	{
		self::initStatic();

		$self = self::getSelfDto();
		if ($self && $self->is_admin) {
			self::$session->set('super_edit_uid', $uid);
		}
	}

	public static function isTa()
	{
		$user = self::getSelfDto();

		if (strpos($user->email, ".ta@") !== false || strpos($user->name, "TA") === 0) {
			return true;
		}

		return false;
	}

	public static function isPressManager()
	{
		$user = self::getSelfDto();
		if ($user === null) {
			return false;
		}

		$press_manager = [
			'kimhs',
			'sanghoon.kim'
		];

		if (in_array($user->id, $press_manager)) {
			return true;
		} else {
			return false;
		}
	}
}
