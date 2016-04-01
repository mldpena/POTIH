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

	$page_row_limit = !empty($memo) ? 19 : 23;
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
		$row_counter = 0;
		$page_amount = 0;

		$pdf->AddPage();
		$pdf->writeHTMLCell('', '', 4, 10, $reference_number, 0, 1, 0, true, 'L', true); // Sold to
		$pdf->writeHTMLCell('', '', 25, 38.5, $customer_displayed_name, 0, 1, 0, true, 'L', true); // Sold to
		$pdf->writeHTMLCell('', '', 174, 38.5, $entry_date, 0, 1, 0, true, 'L', true); // Date
		$pdf->writeHTMLCell('', '', 181, 45, $ponumber, 0, 1, 0, true, 'L', true); // P.O.
		$pdf->writeHTMLCell('', '', 118, 48, $tin, 0, 1, 0, true, 'L', true); // TIN
		$pdf->writeHTMLCell('', '', 181, 51, $drnumber, 0, 1, 0, true, 'L', true); // D.R.
		$pdf->writeHTMLCell('', '', 24, 57, $address, 0, 1, 0, true, 'L', true); // Address


		$pdf->SetFont($font, 'B', 9, '', '', '');
		$pdf->writeHTMLCell('', '', 180, 58, $salesman, 0, 1, 0, true, 'L', true); // Salesman
		$pdf->SetFont($font, 'B', $font_size, '', '', '');

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
			$row_counter++;

			if ($row_counter % $page_row_limit == 0)
				break;

			$table_detail .= '
				<tr>
					<td>'.$detail[$i]['quantity'].' '.$detail[$i]['uom'].'</td>
					<td>'.$detail[$i]['product'].' '.$detail[$i]['description'].'</td>
					<td align="right">'.$detail[$i]['price'].'</td>
					<td align="right">'.$detail[$i]['amount'].'</td>
				</tr>
			';

			$page_amount += str_replace(',', '', $detail[$i]['amount']);
			$product_count = $i;
		}
		
		if (!empty($memo)) 
		{
			$table_detail .= '
				<tr>
					<td colspan="4"></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="3">'.(empty($memo) ? '' : ' Note : '.$memo).'</td>
				</tr>
			';
		}
		

		$table_detail = '
			<table>
				'.$table_detail.'
			</table>
		';

		$vat_amount = $is_vatable == 2 ? ($page_amount * 0.12) / 1.12 : 0;
		$vatable_amount = $is_vatable == 2 ? ($page_amount - $vat_amount) : 0;
		$page_amount_word = str_replace('Dollars', 'Pesos and', ucwords($currency_transformer->toWords($page_amount)));
		$page_amount_word = str_replace('and Zero Cents', '', $page_amount_word);

		$page_amount = number_format($page_amount, 2);
		$vat_amount = number_format($vat_amount, 2);
		$vatable_amount = number_format($vatable_amount, 2);

		$pdf->writeHTMLCell('', '', 4, 74, $table_detail, 0, 1, 0, true, 'L', true); 

		// Amount in words
		$pdf->SetFont($font, 'B', 9, '', '', '');
		$pdf->writeHTMLCell('', '', 37, 202, $page_amount_word, 0, 1, 0, true, 'L', true);
		$pdf->SetFont($font, 'B', $font_size, '', '', '');

		$left_total = '
			<table>
				<tr><td width="100px" align="right">'.$vatable_amount.'</td></tr>
				<tr><td height="25.5px" width="100px" align="right">0.00</td></tr>
				<tr><td width="100px" align="right">0.00</td></tr>
				<tr><td width="100px" align="right">'.$vat_amount.'</td></tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', 27, 209.5, $left_total, 0, 1, 0, true, 'L', true);

		// 2nd amount table
		$right_total = '
			<table>
				<tr><td width="100px" align="right">'.$page_amount.'</td></tr>
				<tr><td height="25.5px" width="100px" align="right">'.$vat_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$vatable_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$page_amount.'</td></tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', 116, 209.5, $right_total, 0, 1, 0, true, 'L', true);

		// Grand total
		$pdf->writeHTMLCell('', '', 180, 221, $page_amount, 0, 1, 0, true, 'L', true);

		if (($product_count + 1) == count($detail))
			break;

		$product_count++;
	}

	$pdf->Output('sales_invoice.pdf', 'I');
?>