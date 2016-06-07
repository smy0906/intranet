<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2016-02-18
 * Time: 오후 6:10
 */

namespace Intra\Service\User;

class UserPolicy
{
	public static function isFirstPageEditable($self)
	{
		if ($self->is_admin || in_array($self->name, ['임다영'])) {
			return true;
		}
		return false;
	}

	public static function isHolidayEditable($self)
	{
		if ($self->is_admin || in_array($self->name, ['임다영'])) {
			return true;
		}
		return false;
	}

	public static function isPressManager($self)
	{
		if ($self->is_admin || in_array($self->name, ['김희수', '김상훈'])) {
			return true;
		}
		return false;
	}

	public static function isUserManager($self)
	{
		if ($self->is_admin || in_array($self->name, ['임다영', '권민석'])) {
			return true;
		}
		return false;
	}

	public static function isPostAdmin($self)
	{
		if ($self->is_admin || in_array($self->name, ['임다영', '권민석', '김미정'])) {
			return true;
		}
		return false;
	}

	public static function isAdmin($self)
	{
		return $self->is_admin;
	}
}
