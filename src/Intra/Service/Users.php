<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 5. 8
 * Time: 오후 4:08
 */

namespace Intra\Service;

use Intra\Core\MsgException;
use Intra\Model\UserFactory;
use Intra\Model\UserModel;
use Symfony\Component\HttpFoundation\Request;

class Users
{
	/**
	 * @param $id
	 * @return User
	 */
	public static function getById($id)
	{
		if (!self::isExistById($id)) {
			return null;
		}
		$uid = UserFactory::getUidById($id);
		return new User($uid);
	}

	private static function isExistById($id)
	{
		return UserFactory:: isExistById($id);
	}

	public static function join($request)
	{
		$joinDto = self::importJoinDtoFromRequest($request);
		self::assertJoin($joinDto);
		UserModel::addUser($joinDto);
	}

	/**
	 * @param $request Request
	 * @return object
	 */
	public static function importJoinDtoFromRequest($request)
	{
		$ret = array();
		$keys = array('name', 'email', 'mobile', 'birth');
		foreach ($keys as $key) {
			$ret[$key] = $request->get($key);
		}
		$ret['id'] = preg_replace('/@.+/', '', $ret['email']);
		return (object)($ret);
	}

	private static function assertJoin($joinDto)
	{
		if (!preg_match("/^.+$/", $joinDto->name)) {
			throw new MsgException('이름을 입력해주세요');
		}
		if (!preg_match("/^.+$/", $joinDto->id)) {
			throw new MsgException('아이디를 입력해주세요');
		}
		if (!preg_match("/^[\w_\.]+$/", $joinDto->id)) {
			throw new MsgException('아이디는 영문과 숫자, 그리고 _(언더바)와 .(점)만 가능합니다');
		}
		if (!preg_match("/^.+@.+\..+$/", $joinDto->email)) {
			throw new MsgException('올바른 이메일을 입력해주세요');
		}
		if (!preg_match("/^\d+\/\d+\/\d+$/", $joinDto->birth) || $joinDto->birth == '0000/00/00') {
			throw new MsgException('생년월일을 올바르게 입력해주세요');
		}
		if (!preg_match("/^\d+-\d+-\d+$/", $joinDto->mobile) || $joinDto->mobile == '010-0000-0000') {
			throw new MsgException('전화번호를 올바르게 입력해주세요');
		}

		if (Users::isExistById($joinDto->id)) {
			throw new MsgException('이미 존재하는 계정입니다');
		}
	}

	public static function getNameByUid($uid)
	{
		$user = self::getByUid($uid);
		if ($user === null) {
			return null;
		}
		return $user->getName();
	}

	/**
	 * @param $uid
	 * @return User
	 */
	public static function getByUid($uid)
	{
		if (!self::isExistByUid($uid)) {
			return null;
		}
		return new User($uid);
	}

	/**
	 * @param $uid
	 * @return bool
	 */
	public static function isExistByUid($uid)
	{
		return UserFactory::isExist($uid);
	}

	public function getAllUsers()
	{
		$uids = UserFactory::getAllUserUid();
		return $this->getUsersByUids($uids);
	}

	/**
	 * @param $uids array
	 * @return User[]
	 */
	public function getUsersByUids($uids)
	{
		$ret = array();
		foreach ($uids as $uid) {
			$ret[] = new User($uid);
		}
		return $ret;
	}
}
