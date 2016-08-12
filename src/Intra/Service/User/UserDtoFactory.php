<?php

namespace Intra\Service\User;

class UserDtoFactory
{

	public static function createFromDatabaseDicts($dicts)
	{
		$return = [];
		foreach ($dicts as $dict) {
			$return[] = UserDto::importFromDatabase($dict);
		}
		return $return;
	}
}
