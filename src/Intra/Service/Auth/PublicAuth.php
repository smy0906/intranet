<?php
namespace Intra\Service\Auth;

use Intra\Service\Auth\Superclass\AuthMultiplexer;
use Intra\Service\User\UserDto;

class PublicAuth extends AuthMultiplexer
{
    /**
     * @param UserDto $user_dto
     *
     * @return bool
     */
    protected function hasAuth(UserDto $user_dto)
    {
        return true;
    }
}
