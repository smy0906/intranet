<?php

namespace Intra\Service\Mail;

class MailingDto
{
    /**
     * @var string[]
     */
    public $receiver;
    /**
     * @var string[]
     */
    public $replyTo;
    /**
     * @var string[]
     */
    public $CC;
    /**
     * @var string[]
     */
    public $BCC;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $body_header;
    /**
     * @var array[]
     */
    public $dicts;
    /**
     * @var string
     */
    public $body_footer;
}
