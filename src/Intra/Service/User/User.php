<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 5. 8
 * Time: 오후 4:35
 */

namespace Intra\Service\User;

use Intra\Config\Config;
use Intra\Model\UserFactory;
use Intra\Model\UserModel;

class User
{
	public $uid;

	public function __construct($uid)
	{
		$this->uid = $uid;
		$this->user_model = new UserModel($uid);
	}

	public static function getbyId($id)
	{
		$uid = UserFactory::getUidById($id);
		if ($uid) {
			return new User($uid);
		}
		return null;
	}

	public function setExtra($key, $value)
	{
		$this->user_model->updateExtra($key, $value);
	}

	public function isSuperAdmin()
	{
		$db_dto = $this->user_model->getDbDto();
		return ($db_dto['is_admin'] == '1');
	}

	public function getName()
	{
		$db_dto = $this->user_model->getDbDto();
		return $db_dto['name'];
	}

	public function getOnDate()
	{
		$db_dto = $this->user_model->getDbDto();
		return $db_dto['on_date'];
	}

	public function getDbDto()
	{
		return $this->user_model->getDbDto();
	}

	public function getEmail()
	{
		return $this->getId() . '@' . Config::$domain;
	}

	public function getId()
	{
		$db_dto = $this->user_model->getDbDto();
		return $db_dto['id'];
	}
}
