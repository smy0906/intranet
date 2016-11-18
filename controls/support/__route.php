<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/{target}')
	->query('index');

$this->matchIf('/{target}/uid/{uid}/yearmonth/{yearmonth}')
	->query('index');

$this->matchIf('/{target}/remain')
	->setRequest('type', 'remain')
	->query('index');

$this->matchIf('/{target}/add')
	->query('add');

$this->matchIf('/{target}/id/{id}')
	->isMethod('put')
	->query('edit');

$this->matchIf('/{target}/id/{id}/complete')
	->isMethod('put')
	->setRequest('type', 'complete')
	->query('edit');

$this->matchIf('/{target}/id/{id}')
	->isMethod('delete')
	->query('del');

$this->matchIf('/{target}/const/{key}')
	->query('const');

$this->matchIf('{target}/file_upload')
	->query('file_upload');

$this->matchIf('{target}/file/{fileid}')
	->assertAsInt('fileid')
	->isMethod('get')
	->query('file_download');

$this->matchIf('{target}/file/{fileid}')
	->assertAsInt('fileid')
	->isMethod('delete')
	->query('file_delete');


$this->matchIf('{target}/download/{type}/{yearmonth}')
	->assertInArray('type', ['year', 'yearmonth'])
	->query('excel_download');
