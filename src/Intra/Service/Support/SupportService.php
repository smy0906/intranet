<?php

namespace Intra\Service\Support;

use Intra\Service\Support\Column\SupportColumnComplete;
use Intra\Service\Support\Column\SupportColumnCompleteDatetime;
use Intra\Service\Support\Column\SupportColumnCompleteUser;
use Intra\Service\Support\Column\SupportColumnRegisterUser;
use Intra\Service\User\UserPolicy;
use Intra\Service\User\UserService;
use Intra\Service\User\UserSession;

class SupportService
{

	public static function getDicts($columns, $target, $uid, $date, $type)
	{
		$self = UserSession::getSelfDto();
		if ($type == 'remain') {
			if (UserPolicy::isSupportAdmin($self)) {
				$column_dicts = SupportModel::getDictsRemainAll($columns, $target);
			} else {
				$column_dicts = SupportModel::getDictsRemain($columns, $target, $uid);
			}
		} else {
			$column_dicts = SupportModel::getDicts($columns, $target, $uid, $date);
		}

		foreach ($column_dicts as &$column_dict) {
			$complete_map = [];
			foreach ($columns as $column) {
				if ($column instanceof SupportColumnComplete) {
					$key = $column->key;
					$complete_map[$key] = $column_dict[$key];
					if ($column_dict[$key]) {
						$column_dict[$key] = '승인됨';
					} else {
						$has_user_auth = ($column->callback_has_user_auth)($self);
						if ($has_user_auth) {
							$column_dict[$key] = '승인가능';
						} else {
							$column_dict[$key] = '승인안됨';
						}
					}
				} elseif ($column instanceof SupportColumnRegisterUser) {
					$key = $column->key;
					$uid = $column_dict[$key];
					$column_dict[$key] = UserService::getNameByUidSafe($uid);
				}
			}

			foreach ($columns as $column) {
				if ($column instanceof SupportColumnCompleteDatetime ||
					$column instanceof SupportColumnCompleteUser
				) {
					$parent_key = $column->parent_column;
					$key = $column->key;
					if ($complete_map[$parent_key]) {
						if ($column instanceof SupportColumnCompleteUser) {
							$column_dict[$key] = UserService::getNameByUidSafe($uid);
						}
					} else {
						unset($column_dict[$key]);
					}
				}
			}
		}

		return $column_dicts;
	}
}
