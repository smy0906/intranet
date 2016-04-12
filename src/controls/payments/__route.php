<?php
/** @var $this Intra\Core\Route */

$this->matchIf('uid/{uid}')
	->assertAsInt('uid')
	->isMethod('get')
	->query('index');

$this->matchIf('remain')
	->setRequest('type', 'remain')
	->query('index');

$this->matchIf('uid/{uid}/month/{month}')
	->assertAsInt('uid')
	->isMethod('get')
	->query('index');

$this->matchIf('uid/{uid}')
	->assertAsInt('uid')
	->isMethod('post')
	->query('add');

$this->matchIf('paymentid/{paymentid}')
	->assertAsInt('paymentid')
	->isMethod('put')
	->query('edit');

$this->matchIf('paymentid/{paymentid}')
	->assertAsInt('paymentid')
	->isMethod('delete')
	->query('del');

$this->matchIf('const/{key}')
	->query('const');

$this->matchIf('download/{month}')
	->query('download');
