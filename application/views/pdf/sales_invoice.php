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

	$pdf->SetFont($font, '', $font_size, '', '', '');

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
	$pdf->writeHTMLCell('', '', 25, 35.5, 'Gian Carlo Egamino Gian Carlo Egamino Gian Carlo Egamino', 0, 1, 0, true, 'L', true); // Sold to
	$pdf->writeHTMLCell('', '', 174, 35.5, '03/04/2016', 0, 1, 0, true, 'L', true); // Date
	$pdf->writeHTMLCell('', '', 181, 42, '12345678', 0, 1, 0, true, 'L', true); // P.O.
	$pdf->writeHTMLCell('', '', 118, 45, '278-931-099-000', 0, 1, 0, true, 'L', true); // TIN
	$pdf->writeHTMLCell('', '', 181, 48, '12345678', 0, 1, 0, true, 'L', true); // D.R.
	$pdf->writeHTMLCell('', '', 24, 54.5, '#1888 GO SOTTO COMPOUND, MJ CUENCO AVE., MABOLO,', 0, 1, 0, true, 'L', true); // Address
	$pdf->writeHTMLCell('', '', 181, 54.5, 'DIONISSA', 0, 1, 0, true, 'L', true); // Salesman

	$tr_product = '';
	for ($i=1; $i <= 19; $i++) { 
		$tr_product .= '
			<tr>
				<td>999 pcs</td>
				<td>BRASS ROUND BAR STREET KNUCKLE PLATINUM IRON</td>
				<td align="right">999.00</td>
				<td align="right">999.00</td>
			</tr>
		';
	}

	// Table Products
	$html_groupedtable = '
		<table>
			<tr>
				<td width="71px"></td>
				<td width="440px"></td>
				<td width="100px" align="right">UNIT PRICE</td>
				<td width="110px"></td>
			</tr>
			'.$tr_product.'
		</table>
	';

	$pdf->writeHTMLCell('', '', 4, 74, $html_groupedtable, 0, 1, 0, true, 'L', true); 

	// 1st amount table
	$html_groupedtable = '
		<table>
			<tr><td width="100px" align="right">33, 941.96</td></tr>
			<tr><td height="25.5px" width="100px" align="right">4, 073.94</td></tr>
			<tr><td width="100px" align="right">0.94</td></tr>
			<tr><td width="100px" align="right">33, 941.96</td></tr>
		</table>
	';

	$pdf->writeHTMLCell('', '', 27, 207.5, $html_groupedtable, 0, 1, 0, true, 'L', true);

	// Amount in words
	$pdf->SetFont($font, '', 9, '', '', '');
	$pdf->writeHTMLCell('', '', 35, 199.5, 'THIRTY-THREE THOUSAND NINE HUNDRED FOURTY ONE PESOS AND NINETY-SIX CENTAVOSESOSES CENTAVOSESOSES', 0, 1, 0, true, 'L', true);
	$pdf->SetFont($font, 'B', $font_size, '', '', '');

	// 2nd amount table
	$html_groupedtable = '
		<table>
			<tr><td width="100px" align="right">33, 941.96</td></tr>
			<tr><td height="25.5px" width="100px" align="right">4, 073.94</td></tr>
			<tr><td width="100px" align="right">0.94</td></tr>
			<tr><td width="100px" align="right">33, 941.96</td></tr>
		</table>
	';

	$pdf->writeHTMLCell('', '', 116, 207.5, $html_groupedtable, 0, 1, 0, true, 'L', true);

	// Grand total
	$pdf->writeHTMLCell('', '', 180, 218, '38, 176.95', 0, 1, 0, true, 'L', true);

	$pdf->Output('sales_invoice.pdf', 'I');
?>