<?php
/** @var $this Intra\Core\Control */
use Intra\Service\Post\Post;

$request = $this->getRequest();
$group = $request->get('group');

$post = new Post();
if ($post->sendAll($group)) {
	return '발송되었습니다';
}
return '발송실패';
