<?php
namespace Intra\Service\Auth;

use Intra\Service\Auth\Superclass\AuthCheckerInterface;
use Intra\Service\User\UserDto;

class PublicAuth implements AuthCheckerInterface
{
	/**
	 * @param UserDto $user_dto
	 *
	 * @return bool
	 */
	public function hasAuth(UserDto $user_dto)
	{
		return true;
	}
}
