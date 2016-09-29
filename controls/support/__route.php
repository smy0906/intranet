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
