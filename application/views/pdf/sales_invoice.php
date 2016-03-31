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

	$page_row_limit = !empty($memo) ? 19 : 22;
	$is_finished = FALSE;
	$product_count = 0;

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

	while ($is_finished == FALSE) 
	{
		$x = $margin_left;
		$y = $margin_top;

		$pdf->AddPage();
		$pdf->writeHTMLCell('', '', 25, 35.5, $customer_displayed_name, 0, 1, 0, true, 'L', true); // Sold to
		$pdf->writeHTMLCell('', '', 174, 35.5, $entry_date, 0, 1, 0, true, 'L', true); // Date
		$pdf->writeHTMLCell('', '', 181, 42, $ponumber, 0, 1, 0, true, 'L', true); // P.O.
		$pdf->writeHTMLCell('', '', 118, 45, $tin, 0, 1, 0, true, 'L', true); // TIN
		$pdf->writeHTMLCell('', '', 181, 48, $drnumber, 0, 1, 0, true, 'L', true); // D.R.
		$pdf->writeHTMLCell('', '', 24, 54.5, $address, 0, 1, 0, true, 'L', true); // Address
		$pdf->writeHTMLCell('', '', 178, 54.5, $salesman, 0, 1, 0, true, 'L', true); // Salesman

		$table_detail = '
			<table>
				<tr>
					<td width="71px"></td>
					<td width="440px"></td>
					<td width="100px" align="right">UNIT PRICE</td>
					<td width="110px"></td>
				</tr>
			</table>
		';

		for ($i= $product_count; $i < count($detail); $i++) 
		{ 
			$product_count = $i;

			if (($i + 1) > $page_row_limit) 
				break;

			$table_detail .= '
				<tr>
					<td>'.$detail[$i]['quantity'].' '.$detail[$i]['uom'].'</td>
					<td>'.$detail[$i]['product'].' '.$detail[$i]['description'].'</td>
					<td align="right">'.$detail[$i]['price'].'</td>
					<td align="right">'.$detail[$i]['amount'].'</td>
				</tr>
			';
		}
		
		$table_detail .= '
			<tr>
				<td></td>
				<td colspan="3">'.(empty($memo) ? '' : ' = '.$memo).'</td>
			</tr>
		';

		$table_detail = '
			<table>
				'.$table_detail.'
			</table>
		';

		$pdf->writeHTMLCell('', '', 4, 74, $table_detail, 0, 1, 0, true, 'L', true); 

		// Amount in words
		$pdf->SetFont($font, 'B', 9, '', '', '');
		$pdf->writeHTMLCell('', '', 35, 199.5, $amount_word, 0, 1, 0, true, 'L', true);
		$pdf->SetFont($font, 'B', $font_size, '', '', '');

		$left_total = '
			<table>
				<tr><td width="100px" align="right">'.$vatable_amount.'</td></tr>
				<tr><td height="25.5px" width="100px" align="right">0.00</td></tr>
				<tr><td width="100px" align="right">0.00</td></tr>
				<tr><td width="100px" align="right">'.$vat_amount.'</td></tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', 27, 207.5, $left_total, 0, 1, 0, true, 'L', true);

		// 2nd amount table
		$right_total = '
			<table>
				<tr><td width="100px" align="right">'.$amount.'</td></tr>
				<tr><td height="25.5px" width="100px" align="right">'.$vat_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$vatable_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$amount.'</td></tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', 116, 207.5, $right_total, 0, 1, 0, true, 'L', true);

		// Grand total
		$pdf->writeHTMLCell('', '', 180, 218, $amount, 0, 1, 0, true, 'L', true);

		if (($product_count + 1) == count($detail))
			$is_finished = TRUE;
	}

	$pdf->Output('sales_invoice.pdf', 'I');
?>