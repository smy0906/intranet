<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Post\PostDetailDto;

$request = $this->getRequest();
$group = $request->get('group');
$id = $request->get('id');

$post_list_view = PostDetailDto::importFromModel($group, $id);
return $post_list_view->exportAsArrayForDetailView();
