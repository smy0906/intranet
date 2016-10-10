<?php

namespace Intra\Service\Menu;

use Intra\Service\Auth\ExceptTaAuthChecker;
use Intra\Service\Auth\Superclass\AuthCheckerInterface;
use Intra\Service\User\UserSession;

class Link
{
	public $is_visible;
	public $title;
	public $url;
	public $target;
	public $glyphicon;

	/**
	 * Link constructor.
	 *
	 * @param                      $title
	 * @param                      $url
	 * @param AuthCheckerInterface $auth_checker
	 * @param null                 $target
	 * @param null                 $glyphicon
	 */
	public function __construct($title, $url, $auth_checker = null, $target = null, $glyphicon = null)
	{
		/**
		 * @var AuthCheckerInterface $auth_checker_instacne
		 */
		if (is_null($auth_checker)) {
			$auth_checker = ExceptTaAuthChecker::class;
		}
		$auth_checker_instacne = new $auth_checker;
		$this->title = $title;
		$this->url = $url;
		$this->is_visible = $auth_checker_instacne->hasAuth(UserSession::getSelfDto());
		$this->target = $target;
		$this->glyphicon = $glyphicon;
	}
}
