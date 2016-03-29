<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'periodic_sales('.date('Y-m-d').').xlsx';
	$header 	= array('INV#', 'DATE', 'CUSTOMER NAME', 'SALESMAN', 'AMOUNT');
	$formats 	= array('String', 'String', 'String', 'String', 'String');
	$align 		= array('Center', 'Center', 'Left', 'Left', 'Right');
	$width 		= array(20, 20, 50, 50, 30);
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