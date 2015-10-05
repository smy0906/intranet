<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-08-16
 * Time: 오후 4:41
 */

namespace Intra\Service\Post;

use Illuminate\Database\Capsule\Manager as Capsule;
use Intra\Config\Config;
use Intra\Core\MsgException;
use Intra\Model\PostModel;
use Intra\Service\UserSession;
use Mailgun\Mailgun;

class Post
{
	public function add($request)
	{
		$postDto = PostDetailDto::importFromWriteRequest($request);
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
		$postDto = PostDetailDto::importFromWriteRequest($request);
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
			function () use (&$result, $group) {
				$posts = PostModel::on()->where('group', $group)->where('is_sent', 0)->get();
				$mail_title = '[공지] ' . date('Y/m/d') . '의 공지사항입니다.';
				$mail_bodys = array();
				foreach ($posts as $post) {
					$mail_body = "<fieldset style='margin: 20px'><legend><h4>{$post->title}</h4> </legend>" . $post->content_html . "</fieldset>";
					$mail_bodys[] = $mail_body;
				}
				$mail_bodys = implode("", $mail_bodys);

				$receivers = array();
				$receivers[] = 'everyone@' . Config::$domain;

				$mg = new Mailgun("***REMOVED***");
				$domain = "ridi.com";
				$result = $mg->sendMessage(
					$domain,
					array(
						'from' => '***REMOVED***',
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
