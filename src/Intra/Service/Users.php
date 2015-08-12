<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 5. 8
 * Time: 오후 4:08
 */

namespace Intra\Service;

use Intra\Core\AjaxMessage;
use Intra\Model\UserFactory;
use Intra\Model\UserModel;
use Symfony\Component\HttpFoundation\Request;

class Users
{
	private static function isExistById($id)
	{
		return UserFactory:: isExistById($id);
	}

	/**
	 * @param $uid
	 * @return bool
	 */
	public static function isExistByUid($uid)
	{
		return UserFactory::isExist($uid);
	}

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

	public static function join($request)
	{
		$joinDto = self::getJoinDtoFromRequest($request);
		self::assertJoin($joinDto);
		UserModel::addUser($joinDto);
	}

	/**
	 * @param $request Request
	 * @return object
	 */
	public static function getJoinDtoFromRequest($request)
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
			throw new AjaxMessage('이름을 입력해주세요');
		}
		if (!preg_match("/^.+$/", $joinDto->id)) {
			throw new AjaxMessage('아이디를 입력해주세요');
		}
		if (!preg_match("/^[\w_\.]+$/", $joinDto->id)) {
			throw new AjaxMessage('아이디는 영문과 숫자, 그리고 _(언더바)와 .(점)만 가능합니다');
		}
		if (!preg_match("/^.+@.+\..+$/", $joinDto->email)) {
			throw new AjaxMessage('올바른 이메일을 입력해주세요');
		}
		if (!preg_match("/^\d+\/\d+\/\d+$/", $joinDto->birth) || $joinDto->birth == '0000/00/00') {
			throw new AjaxMessage('생년월일을 올바르게 입력해주세요');
		}
		if (!preg_match("/^\d+-\d+-\d+$/", $joinDto->mobile) || $joinDto->mobile == '010-0000-0000') {
			throw new AjaxMessage('전화번호를 올바르게 입력해주세요');
		}

		if (Users::isExistById($joinDto->id)) {
			throw new AjaxMessage('이미 존재하는 계정입니다');
		}
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

	public function getAllUsers()
	{
		$uids = UserFactory::getAllUserUid();
		return $this->getUsersByUids($uids);
	}
}
