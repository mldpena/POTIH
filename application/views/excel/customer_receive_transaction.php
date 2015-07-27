<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= 'customer_receive_transactions('.date('Y-m-d').').xlsx';
	$header 	= array('REFERENCE #', 'FROM BRANCH', 'ENTRY DATE', 'MEMO', 'TOTAL QUANTITY', 'STATUS');
	$formats 	= array('String', 'String', 'String', 'String', 'Number-0', 'String');
	$align 		= array('Center', 'Center', 'Center', 'Left', 'Center', 'Center');
	$width 		= array(20, 20, 20, 60, 20, 20);
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