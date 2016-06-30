<?php
/** @var $this Intra\Core\Route */

$this->matchIf('/{userid}/updateExtra/{key}/{value}')
	->query('updateExtra.ajax');

$this->matchIf('/join')
	->isMethod('post')
	->query('join.ajax');
