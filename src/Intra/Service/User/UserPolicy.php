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

	public static function isPaymentAdmin($self)
	{
		if (in_array($self->name, ['설다인', '신은선', '한서윤', '박주현'])) {
			return true;
		}
		return false;
	}

	public static function isReceiptsAdmin($self)
	{
		if ($self->is_admin || in_array($self->name, ['임다영', '권민석'])) {
			return true;
		}
		return false;
	}
}
