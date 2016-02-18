<?php
use Intra\Config\Config;
use Intra\Core\QueryProcessor;
use Intra\Model\SessionModel;
use Intra\Service\IntraDb;
use Intra\Service\Ridi;

$autoloader = require_once(__DIR__ . "/vendor/autoload.php");
$autoloader->add('Intra', __DIR__ . '/src');

Config::loadIfExist(__DIR__ . '/ConfigDevelop.php');
Config::loadIfExist(__DIR__ . '/ConfigRelease.php');

date_default_timezone_set('Asia/Seoul');

Ridi::enableSentry();
IntraDb::bootDB();
SessionModel::init();

if (QueryProcessor::run(__DIR__ . "/src/controls", __DIR__ . "/src/views")) {
	exit;
}
