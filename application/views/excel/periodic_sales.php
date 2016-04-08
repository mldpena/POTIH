<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'periodic_sales('.date('Y-m-d').').xlsx';
	$header 	= array('ITEM#', 'INV#', 'DATE', 'CUSTOMER NAME', 'SALESMAN', 'AMOUNT');
	$formats 	= array('String', 'String', 'String', 'String', 'String', 'String');
	$align 		= array('Center', 'Center', 'Center', 'Left', 'Left', 'Right');
	$width 		= array(10, 20, 20, 50, 50, 30);
	$count 		= 6;

	$writer = new CI_XLSXWriter();
	$writer->setFilename($filename);
	$writer->setAuthor('Hi-Top Merchandise');
	$writer->setHeaderAlign('Center');
	$writer->setHeaderStyle1('Bold');
	$writer->setColumnCount($count);
	$writer->setFormat($formats);
	$writer->setAlign($align);
	$writer->setWidth($width);

	$writer->writeSheetHeader(['', '', '', 'HI TOP MERCHANDISING, INC', '', ''], 'Sheet1');
	$writer->writeSheetHeader(['', '', '', 'PERIODIC SALES REPORT', '', ''], 'Sheet1');
	$writer->writeSheetHeader(['', '', '', 'FROM '.$date_from.' TO '.$date_to, '', ''], 'Sheet1');
	$writer->writeSheetHeader(['', '', '', '', '', ''], 'Sheet1');

	$writer->writeSheetHeader($header, 'Sheet1');

	for ($i=0; $i < count($data); $i++) 
		$writer->customWriteSheet($data[$i], 'Sheet1');
	
	$writer->endSheet('Sheet1');

	$writer->writeToStdOut();

	exit();
?>