<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/index/{month}')
	->query('index');

$this->matchIf('/del/{id}')
	->assertAsInt('id')
	->query('del');

$this->matchIf('/list')
    ->query('list');
