<?php
	ini_set('memory_limit', '128M');

	//ob_start();

	global $pdf, $y, $number_transformer, $page_amount, $note, $is_vat, $font_size, $font, $colspan1_width, $colspan2_width;

	$pdf = new CI_TCPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

	$registry = new \Kwn\NumberToWords\Transformer\TransformerFactoriesRegistry([
	    new \Kwn\NumberToWords\Language\English\TransformerFactory
	]);

	$numberToWords = new \Kwn\NumberToWords\NumberToWords($registry);

	$number_transformer = $numberToWords->getNumberTransformer('en');

	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->setFontSubsetting(false);

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, 12);

	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pdf->SetAutoPageBreak(TRUE, 0);

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	$font = 'arial';
	$font_size = 11.5;

	$margin_left = 9;
	$margin_right = 120;
	$margin_top = 5;
	$note = $memo;
	$is_vat = $is_vatable;
	$limitY = empty($note) ? 205 : 185;
	$widthLimit = 124;
	$linegap = 6;
	$line_height = 5;
	$is_finished = FALSE;
	$print_instruction = FALSE;
	$product_count = 0;
	$description_count = 0;
	
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

	$width = [66, 8, 440, 125, 98];
	$colspan1_width = $width[0] + $width[1];
	$colspan2_width = $width[2] + $width[3] + $width[4];

	while ($is_finished == FALSE) 
	{
		$x = 2;
		$y = $margin_top;
		$page_amount = 0;
		$footer_printed = FALSE;

		$pdf->AddPage();
		//$pdf->writeHTMLCell('', '', 4, 10, $reference_number, 0, 1, 0, true, 'L', true); // Reference No
		$pdf->writeHTMLCell('', '', 25, 34, $customer_displayed_name, 0, 1, 0, true, 'L', true); // Sold to
		$pdf->writeHTMLCell('', '', 178, 34, $entry_date, 0, 1, 0, true, 'L', true); // Date
		$pdf->writeHTMLCell('', '', 183, 40, $ponumber, 0, 1, 0, true, 'L', true); // P.O. 
		$pdf->writeHTMLCell('', '', 120, 43, $tin, 0, 1, 0, true, 'L', true); // TIN
		$pdf->writeHTMLCell('', '', 183, 46, $drnumber, 0, 1, 0, true, 'L', true); // D.R.  
		$pdf->writeHTMLCell('', '', 24, 52, $address, 0, 1, 0, true, 'L', true); // Address


		$pdf->SetFont($font, '', 9, '', '', '');
		$pdf->writeHTMLCell('', '', 183, 53, $salesman, 0, 1, 0, true, 'L', true); // Salesman
		$pdf->SetFont($font, '', $font_size, '', '', '');

		$table_detail = '
			<table>
				<tr>
					<td width="'.$width[0].'px"></td>
					<td width="'.$width[1].'px"></td>
					<td width="'.$width[2].'px"></td>
					<td width="'.$width[3].'px" align="right">UNIT PRICE</td>
					<td width="'.$width[4].'px"></td>
				</tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', $x, 74, $table_detail, 0, 1, 0, true, 'C', true);

		$y = $pdf->GetY();

		for ($i= $product_count; $i < count($detail); $i++) 
		{
			if (!$print_instruction) 
			{
				$currentY = $pdf->GetY();
				$textWidth = $pdf->GetStringWidth($detail[$i]['product'].' '.$detail[$i]['description']);
				$memoWidth = empty($detail[$i]['memo']) ? 0 : $pdf->GetStringWidth($detail[$i]['memo']);

				if (($currentY + ($line_height * ceil($memoWidth / $widthLimit)) + ($line_height * ceil($textWidth / $widthLimit))) >= $limitY) 
				{
					$product_count = $i;
					set_footer();
					$print_footer = TRUE;
					break;
				}

				$table_detail = '
					<table>
						<tr>
							<td align="right" width="'.$width[0].'px">'.$detail[$i]['quantity'].' '.$detail[$i]['uom'].'</td>
							<td width="'.$width[1].'px"></td>
							<td align="left" width="'.$width[2].'px">'.$detail[$i]['product'].' '.$detail[$i]['description'].'</td>
							<td align="right" width="'.$width[3].'px">'.$detail[$i]['price'].'</td>
							<td align="right" width="'.$width[4].'px">'.$detail[$i]['amount'].'</td>
						</tr>
					</table>
				';

				$page_amount += str_replace(',', '', $detail[$i]['amount']);

				$pdf->writeHTMLCell('', '', $x, $y, $table_detail, 0, 1, 0, true, 'C', true);

				$y = $pdf->GetY();

				if($y >= $limitY)
				{
					$product_count = $i;

					if (empty($detail[$i]['memo'])) 
						$product_count++;
					else
						$print_instruction = TRUE;

					if(!$footer_printed)
					{
						set_footer();
						$footer_printed = TRUE;
					}
				}
			}

			if($footer_printed && !($i+1 >= count($detail) && !$print_instruction))
				break;

			if (!empty($detail[$i]['memo']))
			{	
				$html = "
					<table>
						<tr>
							<td colspan = \"2\" style=\"width:".$colspan1_width."px;\"></td>
							<td align=\"left\" style=\"width:".$width[2]."px;\">".$detail[$i]['memo']."</td>
							<td colspan=\"2\"></td>
						</tr>
					</table>";

				$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'L', true);

				$y = $pdf->GetY();

				$print_instruction = FALSE;

				if($y >= $limitY)
				{
					if(!$footer_printed)
					{
						set_footer();

						$footer_printed = TRUE;
					}
				}

				$product_count = $i;
				$product_count++;
				
				if($footer_printed && !($i+1 >= count($detail) && !$print_instruction))
					break;
					
				$y = $pdf->GetY();
			}

			if($i + 1 >= count($detail) && !$print_instruction)
				$is_finished = TRUE;
		}
	}

	if (!$footer_printed) 
	{
		$y = $pdf->GetY();
		set_footer();
	}

	$pdf->Output('sales_invoice.pdf', 'I');

	function set_footer()
	{
		global $pdf, $y, $number_transformer, $page_amount, $note, $is_vat, $font_size, $font, $colspan1_width, $colspan2_width;

		if (!empty($note)) 
		{
			$table_detail = '
				<table>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td colspan="2" style="width:'.$colspan1_width.'px;"></td>
						<td align="left" colspan="3" style="width:'.$colspan2_width.'px;">'.$note.'</td>
					</tr>
				</table>
			';

			$pdf->writeHTMLCell('', '', 2, $y, $table_detail, 0, 1, 0, true, 'L', true); 
		}

		$vat_amount = $is_vat == 2 ? ($page_amount * 0.12) / 1.12 : 0;
		$vatable_amount = $is_vat == 2 ? ($page_amount - $vat_amount) : 0;

		$decimal_amount = ($page_amount - floor($page_amount)) * 100;
		$whole_amount = floor($page_amount);

		$page_amount_word = ucwords($number_transformer->toWords($whole_amount)).' Pesos';
		$page_amount_word .= $decimal_amount == 0 ? '' : ' and '.ucwords($number_transformer->toWords($decimal_amount)).' Cents';

		$vat_exempt_amount = ($is_vat == 2) ? number_format(0, 2) : number_format($page_amount, 2);
		$page_amount = number_format($page_amount, 2);
		$vat_amount = number_format($vat_amount, 2);
		$vatable_amount = number_format($vatable_amount, 2);
		$vat_inclusive = $is_vat == 2 ? $page_amount : number_format(0, 2);

		// Amount in words
		$pdf->SetFont($font, '', 9, '', '', '');
		$pdf->writeHTMLCell('', '', 37, 203, $page_amount_word, 0, 1, 0, true, 'L', true);
		$pdf->SetFont($font, 'B', $font_size, '', '', '');

		$left_total = '
			<table>
				<tr><td width="100px" align="right">'.$vatable_amount.'</td></tr>
				<tr><td height="25.5px" width="100px" align="right">'.$vat_exempt_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$vat_exempt_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$vat_amount.'</td></tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', 27, 210, $left_total, 0, 1, 0, true, 'L', true);

		// 2nd amount table
		$right_total = '
			<table>
				<tr><td width="100px" align="right">'.$vat_inclusive.'</td></tr>
				<tr><td height="25.5px" width="100px" align="right">'.$vat_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$vatable_amount.'</td></tr>
				<tr><td width="100px" align="right">'.$page_amount.'</td></tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', 123, 210, $right_total, 0, 1, 0, true, 'L', true);

		// Grand total

		$grand_total = '
			<table>
				<tr><td width="100px" align="right">'.$page_amount.'</td></tr>
			</table>
		';

		$pdf->writeHTMLCell('', '', 180, 219, $grand_total,  0, 1, 0, true, 'L', true);
	}
?>