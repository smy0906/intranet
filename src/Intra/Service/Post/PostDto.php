<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-08-13
 * Time: 오후 6:28
 */

namespace Intra\Service\Post;

use Illuminate\Database\Eloquent\Model as EloquentBaseModel;
use Intra\Core\BaseDto;
use Intra\Model\PostModel;
use Intra\Service\Users;
use Intra\Service\UserSession;
use Symfony\Component\HttpFoundation\Request;

class PostDto extends BaseDto
{
	public $group;
	public $id;
	public $uid;
	public $title;
	public $content_html;
	public $is_sent;

	/**
	 * @param PostModel|EloquentBaseModel $post
	 * @return PostDto
	 */
	public static function importFromModel($post)
	{
		$return = new self($post->getAttributes());

		return $return;
	}

	public static function importFromWriteRequest(Request $request)
	{
		$return = new self($request);
		$return->uid = UserSession::getSelf()->uid;

		return $return;
	}

	public function exportAsArrayForDetail()
	{
		$return = $this->exportAsArray();
		$return['name'] = Users::getNameByUid($this->uid);
		$return['content_html'] = nl2br($return['content_html']);
		return $return;
	}

	public function exportAsModelForInsertDb()
	{
		$array = $this->exportAsArray();
		unset($array['id']);
		unset($array['is_sent']);
		$return = new PostModel;
		$return->setRawAttributes($array);
		return $return;
	}

	public function exportAsArrayForModify()
	{
		$return = $this->exportAsArray();
		return $return;
	}

	public function exportAsModelForModifyDb()
	{
		$array = $this->exportAsArray();
		$return = new PostModel;
		$return->setRawAttributes($array);
		return $return;
	}
}
