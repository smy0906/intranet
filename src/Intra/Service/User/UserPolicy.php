<?php
namespace Intra\Service\User;

use Intra\Config\Config;

class UserPolicy
{
	public static function isFirstPageEditable(UserDto $self)
	{
		if ($self->is_admin || in_array($self->email, Config::$user_policy['first_page_editable'])) {
			return true;
		}
		return false;
	}

	public static function isHolidayEditable(UserDto $self)
	{
		if ($self->is_admin || in_array($self->email, Config::$user_policy['holiday_editable'])) {
			return true;
		}
		return false;
	}

	public static function isPressManager(UserDto $self)
	{
		if ($self->is_admin || in_array($self->email, Config::$user_policy['press_manager'])) {
			return true;
		}
		return false;
	}

	public static function isUserManager(UserDto $self)
	{
		if ($self->is_admin || in_array($self->email, Config::$user_policy['user_manager'])) {
			return true;
		}
		return false;
	}

	public static function isPostAdmin(UserDto $self)
	{
		if ($self->is_admin || in_array($self->email, Config::$user_policy['post_admin'])) {
			return true;
		}
		return false;
	}

	public static function isPaymentAdmin(UserDto $self)
	{
		if (in_array($self->email, Config::$user_policy['payment_admin'])) {
			return true;
		}
		return false;
	}

	public static function isReceiptsAdmin(UserDto $self)
	{
		if ($self->is_admin || in_array($self->email, Config::$user_policy['receipts_admin'])) {
			return true;
		}
		return false;
	}
}
