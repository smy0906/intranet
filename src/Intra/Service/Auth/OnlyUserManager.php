<?php
namespace Intra\Service\Auth;

use Intra\Service\Auth\Superclass\AuthCheckerInterface;
use Intra\Service\User\UserDto;
use Intra\Service\User\UserPolicy;

class OnlyUserManager implements AuthCheckerInterface
{
	/**
	 * @param UserDto $user_dto
	 *
	 * @return bool
	 */
	public function hasAuth(UserDto $user_dto)
	{
		return UserPolicy::isUserManager($user_dto);
	}
}
