<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= $customer_name.' - Sales('.date('Y-m-d').').xlsx';
	$header 	= array('INV#', 'DATE', 'SALESMAN', 'AMOUNT');
	$formats 	= array('String', 'String', 'String', 'String');
	$align 		= array('Center', 'Center', 'Left', 'Right');
	$width 		= array(20, 20, 50, 30);
	$count 		= 4;

	$writer = new CI_XLSXWriter();
	$writer->setFilename($filename);
	$writer->setAuthor('Hi-Top Merchandise');
	$writer->setHeaderAlign('Center');
	$writer->setHeaderStyle1('Bold');
	$writer->setColumnCount($count);
	$writer->setFormat($formats);
	$writer->setAlign($align);
	$writer->setWidth($width);
	$writer->setBorder(1);

	$writer->writeSheetHeader($header, 'Sheet1');

	for ($i=0; $i < count($data); $i++) 
	{
		$writer->customWriteSheet($data[$i], 'Sheet1');
	}	
	
	$writer->endSheet('Sheet1');

	$writer->writeToStdOut();

	exit();
?>