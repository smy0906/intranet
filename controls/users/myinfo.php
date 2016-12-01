<?php
/**
 * Created by PhpStorm.
 * User: imkkt
 * Date: 2016. 11. 30.
 * Time: PM 3:54
 */

use Intra\Service\User\UserSession;

$dto = UserSession::getSelfDto();

return [
	'uid' => $dto->uid,
	'list' => [
		'id' => ['아이디', $dto->id],
		'name' => ['이름', $dto->name],
		'team' => ['팀', $dto->team],
		'birth' => ['생년월일', $dto->birth],
		'mobile' => ['전화번호', $dto->mobile],
		'email' => ['이메일', $dto->email],
	],
	'image' => $dto->image,
	'comment' => $dto->comment,
];
