<?php
/** @var $this Intra\Core\Control */

use Intra\Model\LightFileModel;
use Intra\Service\Weekly;

date_default_timezone_set('Asia/Seoul');

$infile = $_FILES["fileToUpload"]["tmp_name"];
$filebag = new LightFileModel('weekly');
$outfile = $filebag->getLocation(date("Ym") . '-' . floor((date('d') - 1) / 7 + 1) . ".html");

if ($infile) {
	Weekly::dumpToHtml($infile, $outfile);
}
return array();
