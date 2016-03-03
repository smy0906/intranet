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
use Intra\Service\User\UserInstanceService;
use Intra\Service\User\UserSession;
use Symfony\Component\HttpFoundation\Request;

class PostDetailDto extends BaseDto
{
	public $group;
	public $id;
	public $uid;
	public $title;
	public $content_html;
	public $is_sent;
	public $updated_at;

	public $is_new;

	/**
	 * @param PostModel|EloquentBaseModel $post
	 * @return PostDetailDto
	 */
	public static function importFromModel($post)
	{
		$return = new self;
		$return->initFromArray($post->getAttributes());
		if (strtotime('-1 day') < strtotime($return->updated_at)) {
			$return->is_new = true;
		} else {
			$return->is_new = false;
		}

		return $return;
	}

	public static function importFromWriteRequest(Request $request)
	{
		$return = new self;
		$return->initFromRequest($request);
		$return->uid = UserSession::getSelfDto()->uid;

		return $return;
	}

	public function exportAsArrayForDetailView()
	{
		$return = $this->exportAsArray();
		$return['name'] = UserInstanceService::importFromDatabaseWithUid($this->uid)->getName();
		$return['content_html'] = nl2br($return['content_html']);
		return $return;
	}

	public function exportAsModelForInsertDb()
	{
		$array = $this->exportAsArrayByKeys(['group', 'uid', 'title', 'content_html']);
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
		$array = $this->exportAsArrayByKeys(['group', 'uid', 'title', 'content_html', 'id']);
		$return = new PostModel;
		$return->setRawAttributes($array);
		return $return;
	}
}
