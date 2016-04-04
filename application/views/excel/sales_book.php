<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'Sales Book('.date('Y-m-d').').xlsx';
	$header 	= array('Date', 'Invoice No.', 'Company Name', 'Invoice Amount', 'VATable Amount', 'VAT Amount', 'VAT Exempt Amount');
	$formats 	= array('String', 'String', 'String', 'Money-2', 'Money-2', 'Money-2', 'Money-2');
	$align 		= array('Center', 'Center', 'Left', 'Right', 'Right', 'Right', 'Right');
	$width 		= array(20, 30, 60, 20, 20, 20, 20);
	$count 		= 7;

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

	for ($i=0; $i < count($data); $i++) 
	{ 
		if ($i == 0) 
			$writer->customWriteSheet($data[$i],'Sheet1', $header);
		else
			$writer->customWriteSheet($data[$i],'Sheet1');
	}	
	
	$writer->endSheet('Sheet1');

	$writer->writeToStdOut();

	exit();
?>