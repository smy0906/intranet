<?php

use Intra\Service\User\UserSession;

$dto = UserSession::getSelfDto();

return [
	'uid' => $dto->uid,
	'title' => $dto->name,
	'info' => [
		'body' => [
			//'db key' => ['name', 'value', 'isEditable']
			'id' => ['아이디', $dto->id, false],
			'name' => ['이름', $dto->name, false],
			'team' => ['팀', $dto->team, false],
			'birth' => ['생년월일', $dto->birth, true],
			'mobile' => ['전화번호', $dto->mobile, true],
			'email' => ['이메일', $dto->email, false],
		],
		'image' => $dto->image,
		'comment' => $dto->comment,
	]
];
