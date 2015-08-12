<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 1. 7
 * Time: 오후 3:50
 */

namespace Intra\Service;

use Intra\Model\SessionModel;
use Intra\Model\UserFactory;
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

		UserFactory::assertUserIdExist($id);
		$user = User::getbyId($id);
		self::$session->set('users_uid', $user->uid);

		return 1;
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
	 * @return User
	 * "현재 편집중인 유저" or "본인"
	 */
	public static function getSupereditUser()
	{
		self::initStatic();

		$super_edit_uid = self::$session->get('super_edit_uid');
		$self = self::getSelf();

		if ($super_edit_uid && $self && $self->isSuperAdmin()) {
			return Users::getByUId($super_edit_uid);
		} else {
			return $self;
		}
	}

	/**
	 * @return User
	 */
	public static function getSelf()
	{
		self::initStatic();

		if (!self::isLogined()) {
			return null;
		}
		if (self::$session->get('users_uid')) {
			$users_uid = self::$session->get('users_uid');
			return Users::getByUid($users_uid);
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

		$self = self::getSelf();
		if ($self && $self->isSuperAdmin()) {
			self::$session->set('super_edit_uid', $uid);
		}
	}
}
