<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/index/month/{month}')
	->assertAsInt('month')
	->query('index');

$this->matchIf('/{userid}/updateExtra/{key}/{value}')
	->query('updateExtra.ajax');

$this->matchIf('/join')
	->isMethod('post')
	->query('join.ajax');
