<?php

	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'inventory_adjustments('.date('Y-m-d').').xlsx';
	$header 	= array('MATERIAL CODE', 'PRODUCT', 'TYPE', 'FROM BRANCH', 'DATE', 'OLD INVENTORY', 'CURRENT INVENTORY', 'ADJUSTED INVENTORY', 'STATUS', 'MEMO');
	$formats 	= array('String', 'String', 'String', 'String', 'String', 'Number-0', 'Number-0', 'Number-0', 'String', 'String');
	$align 		= array('Center', 'Left', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Left');
	$width 		= array(20, 60, 20, 20, 20, 20, 30, 30, 20, 60);
	$count 		= 10;

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