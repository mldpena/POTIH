<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'product_transactions('.date('Y-m-d').').xlsx';
	$header 	= array('MATERIAL CODE', 'PRODUCT', 'TYPE', 'BEGINNING INVENTORY', 'PURCHASE RECEIVE', 'CUSTOMER RETURN', 'ITEM RECEIVE', 'ADJUST INCREASE', 'DAMAGE', 'PURCHASE RETURN', 'ITEM DELIVERY', 'CUSTOMER DELIVERY', 'ADJUST DECREASE', 'WAREHOUSE RELEASE', 'TOTAL INVENTORY');
	$formats 	= array('String', 'String', 'String', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0', 'Number-0');
	$align 		= array('Center', 'Left', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center');
	$width 		= array(20, 60, 20, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30);
	$count 		= 15;

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