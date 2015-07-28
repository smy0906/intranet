<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/add/{key}/{value}')
	->assertInArray('key', array('program', 'font'))
	->query('add');
