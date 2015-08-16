<?php
/** @var $this Intra\Core\Control */

use Intra\Model\PostModel;
use Intra\Service\Post\PostDto;

$request = $this->getRequest();
$group = $request->get('group');
$id = $request->get('id');

$post_list_view = PostDto::importFromModel(PostModel::on()->find($id));
return $post_list_view->exportAsArrayForDetail();
