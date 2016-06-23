<?php

namespace Intra\Service\Cron;

class MailingDto
{
	/**
	 * @var $receiver string[]
	 */
	public $receiver;
	/**
	 * @var $replyTo string[]
	 */
	public $replyTo;
	/**
	 * @var $CC string[]
	 */
	public $CC;
	/**
	 * @var $BCC string[]
	 */
	public $BCC;
	/**
	 * @var $title string
	 */
	public $title;
	/**
	 * @var $body_header string
	 */
	public $body_header;
	/**
	 * @var $dicts array[]
	 */
	public $dicts;
	/**
	 * @var $body_footer string
	 */
	public $body_footer;
}
