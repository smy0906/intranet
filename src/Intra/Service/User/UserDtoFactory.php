<?php

namespace Intra\Service\User;

use Intra\Model\UserModel;

class UserDtoFactory
{
	/**
	 * @param $uid
	 *
	 * @return UserDto
	 */
	public static function getDtobyUid($uid)
	{
		if (!UserModel::isExistByUid($uid)) {
			return null;
		}
		$dict = UserModel::getDictWithUid($uid);
		return UserDto::importFromDatabase($dict);
	}

	public static function createFromDatabaseDicts($dicts)
	{
		$return = [];
		foreach ($dicts as $dict) {
			$return[] = UserDto::importFromDatabase($dict);
		}
		return $return;
	}
}
