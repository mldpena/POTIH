<?php
	$filename 	= "products(".date("Y-m-d").").xlsx";
	$header 	= array('QTY','DATE','WAYBILL #','CONSIGNEE NAME','DESTINATION','POUCH','WEIGHT','ITEMS','AMOUNT','PARCEL TYPE');
	$formats 	= array("Number-0", "String", "Number-0", "String", "String","String","String","String","Money-2","String");
	$align 		= array("Center","Center","Center","Left","Left","Center","Center","Left","Right","Center");
	$width 		= array(20,20,20,20,20,20,20,60,20,20);
	$count 		= 9;

	$writer = new CI_XLSXWriter();
	$writer->setFilename($filename);
	$writer->setAuthor('Jaca Express');
	$writer->setHeaderAlign("Center");
	$writer->setHeaderStyle1("Bold");
	$writer->setColumnCount($count);
	$writer->setFormat($formats);
	$writer->setAlign($align);
	$writer->setWidth($width);
	$writer->setBorder(1);

	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';
	$data[0][] = '1';

	for ($i=0; $i < count($data); $i++) { 
		if ($i == 0) {
			$writer->customWriteSheet($data[$i],'Sheet1', $header);
		}else{
			$writer->customWriteSheet($data[$i],'Sheet1');
		}
		
	}	
	
	$writer->endSheet('Sheet1');

	$writer->writeToStdOut();

	exit(0);
?>