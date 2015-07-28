<?php
/** @var $this Intra\Core\Control */

use Intra\Model\lightFileModel;

date_default_timezone_set('Asia/Seoul');

function dumpToHtml($infile, $outfile)
{
	$reader = new PHPExcel_Reader_Excel2007();
	$excel = $reader->load($infile);

	$writer = new PHPExcel_Writer_HTML($excel);
	$writer->save($outfile);
}

$infile = $_FILES["fileToUpload"]["tmp_name"];
$filebag = new lightFileModel('weekly');
$outfile = $filebag->getLocation(date("Ym") . '-' . floor((date('d') - 1) / 7 + 1) . ".html");

if ($infile) {
	dumpToHtml($infile, $outfile);
}
return array();
