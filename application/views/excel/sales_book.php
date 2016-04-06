<?php
	if ($rowcnt == 0) 
	{
		echo 'No items to print!';
		exit();
	}

	$filename 	= $branch_name.' Sales Book('.date('Y-m-d').').xlsx';
	$header 	= array('Date', 'Invoice No.', 'Company Name', 'Invoice Amount', 'VATable Amount', 'VAT Amount', 'VAT Exempt Amount');
	$formats 	= array('String', 'String', 'String', 'Money-2', 'Money-2', 'Money-2', 'Money-2');
	$align 		= array('Center', 'Center', 'Left', 'Right', 'Right', 'Right', 'Right');
	$width 		= array(30, 30, 60, 20, 20, 20, 20);
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

	$writer->writeSheetHeader(['', '', 'HI TOP MERCHANDISING, INC', '', '', '', ''], 'Sheet1');
	$writer->writeSheetHeader(['', '', $branch_name.' SALES BOOK', '', '', '', ''], 'Sheet1');
	$writer->writeSheetHeader(['', '', 'FROM '.$date_from.' TO '.$date_to, '', '', '', ''], 'Sheet1');
	$writer->writeSheetHeader(['', '', '', '', '', '', ''], 'Sheet1');

	$writer->writeSheetHeader($header, 'Sheet1');

	for ($i=0; $i < count($data); $i++) 
		$writer->customWriteSheet($data[$i], 'Sheet1');
	
	$writer->customWriteSheetTotal([$total_amount, $total_vatable_amount, $total_vat_amount, $total_vat_exempt_amount], [3, 4, 5, 6]);

	//$writer->writeSheetHeader(['', '', 'GRAND TOTAL:', $total_amount, $total_vatable_amount, $total_vat_amount, $total_vat_exempt_amount], 'Sheet1');

	$writer->endSheet('Sheet1');

	$writer->writeToStdOut();

	exit();
?>