<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'inventory_warning('.date('Y-m-d').').xlsx';
	$header 	= array('MATERIAL CODE', 'PRODUCT', 'TYPE', 'MATERIAL TYPE', 'SUBGROUP', 'MIN INVENTORY', 'MAX INVENTORY', 'INVENTORY', 'STATUS');
	$formats 	= array('String', 'String', 'String', 'String', 'String', 'Number-0', 'Number-0', 'Number-0', 'String');
	$align 		= array('Center', 'Left', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center', 'Center');
	$width 		= array(20, 60, 20, 40, 40, 20, 20, 20, 20);
	$count 		= 9;

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