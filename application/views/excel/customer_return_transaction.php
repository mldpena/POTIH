<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'customer_return_transactions('.date('Y-m-d').').xlsx';
	$header 	= array('LOCATION', 'REFERENCE #', 'ENTRY DATE', 'CUSTOMER', 'MEMO');
	$formats 	= array('String', 'String', 'String', 'String', 'String');
	$align 		= array('Center', 'Center', 'Center', 'Center', 'Left');
	$width 		= array(20, 20, 20, 20, 60);
	$count 		= 5;

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