<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-02-18
 * Time: 오후 1:57
 */

namespace Intra\Service\User;


use Exception;
use Intra\Config\Config;
use Intra\Core\BaseDtoObject;
use Intra\Model\UserModel;

class UserDtoObject extends BaseDtoObject
{
	/**
	 * @var UserDto
	 */
	protected $dto;

	/**
	 * @param $id
	 * @return UserDtoObject
	 * @throws Exception
	 */
	public static function importFromDatabaseWithId($id)
	{
		$uid = UserModel::convertUidFromId($id);
		$row = UserModel::getRowWithUid($uid);
		self::assertDatabaseRowExist($row);
		return self::importFromDto(UserDto::importFromDatabase($row));
	}

	/**
	 * @param $uid
	 * @return UserDtoObject
	 * @throws Exception
	 */
	public static function importFromDatabaseWithUid($uid)
	{
		$row = UserModel::getRowWithUid($uid);
		self::assertDatabaseRowExist($row);
		return self::importFromDto(UserDto::importFromDatabase($row));
	}

	public function isValid()
	{
		if ($this->dto->on_date == '9999-01-01') {
			return false;
		}
		if (strtotime($this->dto->off_date) < time()) {
			return false;
		}
		return true;
	}

	public function getName()
	{
		return $this->dto->name;
	}

	public function setExtra($key, $value)
	{
		if (is_null($value)) {
			unset($this->dto->extra[$key]);
		} else {
			$this->dto->extra[$key] = $value;
		}
		$extra_update = $this->dto->exportExtraForDatabase();
		UserModel::updateExtra($this->dto->uid, $extra_update);
	}

	public function isSuperAdmin()
	{
		return ($this->dto->is_admin == '1');
	}

	public function getOnDate()
	{
		return $this->dto->on_date;
	}

	public function getEmail()
	{
		return $this->getId() . '@' . Config::$domain;
	}

	public function getId()
	{
		return $this->dto->id;
	}

	public function getUid()
	{
		return $this->dto->uid;
	}
}
