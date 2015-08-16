<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/{group}')
	->query('index');

$this->matchIf('/{group}/write')
	->isMethod('get')
	->query('write');

$this->matchIf('/{group}/sendAll')
	->query('sendAll');

$this->matchIf('/{group}/{id}/modify')
	->assertAsInt('id')
	->isMethod('get')
	->query('modify');

$this->matchIf('/{group}/{id}/modify')
	->assertAsInt('id')
	->isMethod('post')
	->query('modify.ajax');

$this->matchIf('/{group}/{id}/delete')
	->assertAsInt('id')
	->query('delete');

$this->matchIf('/{group}/{id}')
	->assertAsInt('id')
	->query('view');
