<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/{holidayid}/del')
	->assertAsInt('holidayid')
	->query('del');

$this->matchIf('/index/year/{year}')
	->assertAsInt('year')
	->query('index');

$this->matchIf('/download/{year}')
	->assertAsInt('year')
	->query('download');

$this->matchIf('/downloadRemain/{year}')
	->assertAsInt('year')
	->query('downloadRemain');
