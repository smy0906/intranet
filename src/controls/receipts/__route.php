<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/index/{month}')
	->query('index');

$this->matchIf('/del/{receiptid}')
	->assertAsInt('receiptid')
	->query('del');

$this->matchIf('/download/{month}')
	->query('download');

$this->matchIf('/downloadYear/{month}')
	->query('downloadYear');
