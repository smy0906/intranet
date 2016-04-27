<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/get')
	->query('get');

$this->matchIf('/del/id/{id}')
	->assertAsInt('id')
	->query('del');

$this->matchIf('/mod/id/{id}')
	->assertAsInt('id')
	->query('mod');
