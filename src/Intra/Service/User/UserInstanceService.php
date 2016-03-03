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
use Intra\Core\BaseInstanceService;
use Intra\Model\UserModel;

class UserInstanceService extends BaseInstanceService
{
	/**
	 * @var UserDto
	 */
	protected $dto;

	/**
	 * @param $id
	 * @return UserInstanceService
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
	 * @return UserInstanceService
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

	public function updateByKey($key, $value)
	{
		list($key, $value) = $this->filterUpdate($key, $value);
		$this->assertUpdate($key, $value);
		$this->dto->$key = $value;
		$update = $this->dto->exportForDatabaseOnlyKeys([$key]);
		UserModel::update($this->dto->uid, $update);
	}

	private function filterUpdate($key, $value)
	{
		$value = trim($value);
		return [$key, $value];
	}

	private function assertUpdate($key, $value)
	{
		if (in_array($key, ['on_date', 'off_date', 'birth'])) {
			$time = strtotime($value);
			if ($time === false) {
				throw new Exception("날짜형식이 틀렸습니다.");
			}
		}
		if (!UserSession::isUserManager()) {
			throw new Exception("권한이 없습니다");
		}
	}
}
