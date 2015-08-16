<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-08-13
 * Time: 오후 6:25
 */

namespace Intra\Service\Post;

use Intra\Model\PostModel;
use Intra\Service\Users;

class PostListDto
{
	private $group;
	/**
	 * @var PostModel[]
	 */
	private $posts;
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

		$return->paginate = PostModel::on()->where('group', $post_group)->paginate(15);
		$return->posts = $return->paginate->items();

		return $return;
	}

	public function exportAsArrayForTwig()
	{
		foreach ($this->posts as $post) {
			$uid_name = Users::getNameByUid($post->uid);
			$post->uid_name = $uid_name;
		}

		return array(
			'group' => $this->group,
			'posts' => $this->posts,
			'paginate' => $this->paginate,
		);
	}
}
