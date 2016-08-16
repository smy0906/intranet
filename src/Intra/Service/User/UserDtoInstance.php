<?php
namespace Intra\Service\User;

use Exception;
use Intra\Config\Config;
use Intra\Core\BaseDtoHandler;
use Intra\Model\UserModel;

class UserDtoHandler extends BaseDtoHandler
{
	/**
	 * @var UserDto
	 */
	protected $dto;

	/**
	 * @param $id
	 * @return UserDtoHandler
	 * @throws Exception
	 */
	public static function importFromDatabaseWithId($id)
	{
		$uid = UserModel::convertUidFromId($id);
		$row = UserModel::getDictWithUid($uid);
		self::assertDatabaseRowExist($row);
		return self::importFromDto(UserDto::importFromDatabase($row));
	}

	/**
	 * @param $uid
	 * @return UserDtoHandler
	 * @throws Exception
	 */
	public static function importFromDatabaseWithUid($uid)
	{
		$dict = UserModel::getDictWithUid($uid);
		self::assertDatabaseRowExist($dict);
		return self::importFromDto(UserDto::importFromDatabase($dict));
	}

	public function isValid()
	{
		$is_safe_ondate = ($this->dto->on_date == '9999-01-01');
		if ($is_safe_ondate) {
			return false;
		}
		$is_safe_offdate = ($this->dto->off_date != '9999-01-01' && strtotime($this->dto->off_date) < time());
		if ($is_safe_offdate) {
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
