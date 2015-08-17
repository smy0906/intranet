<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-08-13
 * Time: 오후 6:25
 */

namespace Intra\Service\Post;

use Intra\Model\PostModel;

class PostListDto
{
	private $group;
	/**
	 * @var PostModel[]
	 */
	private $postModels;
	/**
	 * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private $paginate;

	public function __construct($group)
	{
		$this->group = $group;
	}

	public static function import($post_group, $page = 0)
	{
		$return = new self($post_group);

		$return->paginate = PostModel::on()->where('group', $post_group)->orderBy('id', 'desc')->paginate(15);
		$return->postModels = $return->paginate->items();

		return $return;
	}

	public function exportAsArrayForListView()
	{
		$posts = array();
		foreach ($this->postModels as $postModel) {
			$postDto = PostDetailDto::importFromModel($postModel);
			$post = $postDto->exportAsArrayForDetailView();
			$posts[] = $post;
		}

		return array(
			'group' => $this->group,
			'posts' => $posts,
			'lastItem' => $this->paginate->lastItem(),
		);
	}
}
