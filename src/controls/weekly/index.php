<?php
/** @var $this Intra\Core\Control */

use Intra\Service\Weekly;

$weekly = new Weekly;

try {
	$weekly->assertPermission();
	return array('html' => $weekly->getContents());
} catch (Exception $e) {
	die($e->getMessage());
}
