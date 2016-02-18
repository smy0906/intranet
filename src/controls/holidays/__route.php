<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/index/uid/{uid}')
	->assertAsInt('uid')
	->query('index');

$this->matchIf('/index/uid/{uid}/year/{year}')
	->assertAsInt('uid')
	->assertAsInt('year')
	->query('index');

$this->matchIf('/download/{year}')
	->assertAsInt('year')
	->query('download');

$this->matchIf('/downloadRemain/{year}')
	->assertAsInt('year')
	->query('downloadRemain');

$this->matchIf('/uid/{uid}/{holidayid}/del')
	->assertAsInt('uid')
	->assertAsInt('holidayid')
	->query('del');

$this->matchIf('/uid/{uid}/edit')
	->assertAsInt('uid')
	->query('edit');


