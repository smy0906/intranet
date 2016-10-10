<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Support\Column\SupportColumnCategory;
use Intra\Service\Support\Column\SupportColumnTeam;
use Intra\Service\Support\Column\SupportColumnWorker;
use Intra\Service\Support\SupportPolicy;
use Intra\Service\User\UserConstant;
use Intra\Service\User\UserDtoFactory;
use Intra\Service\User\UserJoinService;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\JsonResponse;

$self = UserSession::getSelfDto();

$request = $this->getRequest();
$target = $request->get('target');
$key = $request->get('key');

$columns = SupportPolicy::getColumns($target);
$return = [];
foreach ($columns as $column) {
	if ($key == $column->key) {
		if ($column instanceof SupportColumnTeam) {
			foreach (UserConstant::$jeditable_key_list['team'] as $team) {
				$return[$team] = $team;
			}
		} elseif ($column instanceof SupportColumnWorker) {
			foreach (UserDtoFactory::createAvailableUserDtos() as $user_dto) {
				$return[$user_dto->uid] = $user_dto->name;
			}
		} elseif ($column instanceof SupportColumnCategory) {
			foreach ($column->category_items as $category_item) {
				$return[$category_item] = $category_item;
			}
		}
	}
}
return new JsonResponse($return);
