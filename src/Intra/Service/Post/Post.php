<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-08-16
 * Time: 오후 4:41
 */

namespace Intra\Service\Post;

use Illuminate\Database\Capsule\Manager as Capsule;
use Intra\Core\MsgException;
use Intra\Model\PostModel;
use Intra\Service\UserSession;
use Mailgun\Mailgun;

class Post
{
	public function add($request)
	{
		$postDto = PostDto::importFromWriteRequest($request);
		$this->assertAdd($postDto);
		/**
		 * @var PostModel
		 */
		$post = $postDto->exportAsModelForInsertDb();
		$post->save();
	}

	private function assertAdd($post_list_view)
	{
		if (!UserSession::getSelf()->isSuperAdmin()) {
			throw new MsgException('권한이 없습니다');
		}
	}

	public function modify($request)
	{
		$postDto = PostDto::importFromWriteRequest($request);
		$this->assertModify($postDto);

		$post = PostModel::on()->find($postDto->id);
		$post->update($postDto->exportAsArrayForModify());
		$post->save();
	}

	private function assertModify($post_list_view)
	{
		if (!UserSession::getSelf()->isSuperAdmin()) {
			throw new MsgException('권한이 없습니다');
		}
	}

	public function sendAll($group)
	{
		$result = false;
		Capsule::connection()->transaction(
			function () use ($result, $group) {
				$posts = PostModel::on()->where('group', $group)->where('is_sent', 0)->get();
				$mail_title = '[공지] ' . date('Y/m/d');
				$mail_bodys = array();
				foreach ($posts as $post) {
					$mail_body = " - {$post->title}\n\n" . $post->content_html;
					$mail_bodys[] = $mail_body;
				}
				$mail_bodys = implode("\n\n\n", $mail_bodys);

				$receivers = array();
				$receivers[] = '***REMOVED***';
				$receivers[] = '***REMOVED***';

				$mg = new Mailgun("***REMOVED***");
				$domain = "ridibooks.com";
				$result = $mg->sendMessage(
					$domain,
					array(
						'from' => 'noreply@ridibooks.com',
						'to' => implode(', ', $receivers),
						'subject' => $mail_title,
						'text' => strip_tags($mail_bodys),
						'html' => nl2br($mail_bodys),
					)
				);
				foreach ($posts as $post) {
					$post->is_sent = true;
					$post->save();
				}
				$result = true;
			}
		);
		return $result;
	}
}
