<?php
	ini_set('memory_limit', '128M');

	//ob_start();

	$pdf = new CI_TCPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->setFontSubsetting(false);

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, 12);

	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pdf->SetAutoPageBreak(TRUE, 0);

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	$font = 'arial';
	$font_size = 12;

	$margin_left = 10;
	$margin_right = 120;
	$margin_top = 5;

	$half_page_y = 147;
	$whole_page_y = 278;

	$linegap = 6;

	$is_finished = FALSE;
	$page_number = 0;
	$product_count = 0;
	$description_count = 0;
	$print_description = FALSE;

	$style = "
		<style type='text/css'>
			.tdleft {
				text-align : left;
			}

			.tdcenter {
				text-align : center;
			}

			table {
				border-bottom-style : solid;
			}

			.header-border {
				border-top-style : solid;
				border-left-style : solid;
				border-right-style : solid;
			} 

			.table-data {
				border-left-style : solid;
				border-right-style : solid;
			}
		</style>
	";

	$x = $margin_left;
	$y = $margin_top;

	$pdf->AddPage();
	$pdf->writeHTMLCell('', '', $x, $y, 'aw', 0, 1, 0, true, 'C', true);


	$pdf->Output('sales_invoice.pdf', 'I');
?>