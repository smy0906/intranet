<?php

namespace Intra\Service\Auth\Superclass;

use Intra\Service\User\UserDto;

interface AuthCheckerInterface
{
	/**
	 * @param UserDto $user_dto
	 *
	 * @return bool
	 */
	public function hasAuth(UserDto $user_dto);
}
