<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/index/{month}')
	->query('index');

$this->matchIf('/del/{paymentid}')
	->assertAsInt('paymentid')
	->query('del');

$this->matchIf('/const/{key}')
	->query('const');

$this->matchIf('/download/{month}')
	->query('download');
