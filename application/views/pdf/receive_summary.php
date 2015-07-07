<?php
	ini_set('memory_limit', '128M');

	function set_footer($pdf,$y)
	{
		$y += 6;
		$x = 10;

		$pdf->writeHTMLCell('', '', $x, $y,'Summary Report Done by : _____________________', 0, 1, 0, true, 'L', true);

		$x = 165;

		$pdf->writeHTMLCell('', '', $x, $y,'Signature : _____________________', 0, 1, 0, true, 'L', true);
	}

	//ob_start();

	$pdf = new TCPDF('L', PDF_UNIT, 'FOLIO', true, 'UTF-8', false);

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

	$linegap = 6;

	$is_finished = FALSE;
	$product_count = 0;
	$print_description = FALSE;

	$pdf->SetFont($font,'B',$font_size,'','','');

	$style = "
		<style type='text/css'>
			.tdleft{
				text-align: left;
			}

			.tdcenter{
				text-align: center;
			}
		</style>
	";

	$column_width = array("60px","435px","95px","105px","400px");

	while ($is_finished == FALSE) 
	{
		$x = $margin_left;
		$y = $margin_top;

		$pdf->AddPage();

		$pdf->SetFont($font,'B',20,'','','');

		$pdf->writeHTMLCell('', '', $x, $y,'HI-TOP MERCHANDISING, INC.', 0, 1, 0, true, 'C', true);

		$y+= $linegap;

		$pdf->writeHTMLCell('', '', $x, $y,'RECEIVING SUMMARY', 0, 1, 0, true, 'C', true);

		$y+= $linegap * 2;

		$pdf->writeHTMLCell('', '', $x, $y,'No. # '.$reference_number, 0, 1, 0, true, 'L', true);

		$pdf->SetFont($font,'B',$font_size,'','','');

		$pdf->writeHTMLCell('', '', $x, $y,'Date : '.date("M d, Y",strtotime($entry_date)), 0, 1, 0, true, 'R', true);

		$y+= $linegap * 2;

		$html = <<<EOD
				$style
				<table>
					<tr>
						<td style="width:$column_width[0];" class="tdleft">Qty</td>
						<td style="width:$column_width[1];">Item Description</td>
						<td style="width:$column_width[2];">Item Code</td>
						<td style="width:$column_width[3];">Rcvd By</td>
						<td style="width:$column_width[4];">Note</td>
					</tr>
				</table>
EOD;
		$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'C', true);

		$y+= $linegap;

		for ($i = $product_count; $i < count($detail); $i++) 
		{ 
			if (!$print_description) 
			{
				$html = "
					<table>
						<tr>
							<td style=\"width:".$column_width[0].";\">".$detail[$i]["quantity"]."</td>
							<td style=\"width:".$column_width[1].";\">".$detail[$i]["product"]."</td>
							<td style=\"width:".$column_width[2].";\">".$detail[$i]["item_code"]."</td>
							<td style=\"width:".$column_width[3].";\">".$detail[$i]["received_by"]."</td>
							<td style=\"width:".$column_width[4].";\">".$detail[$i]["receive_memo"]."</td>
						</tr>
					</table>";

				$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'L', true);

				$y = $pdf->GetY();

				if($y >= 200)
				{
					$product_count = $i;

					if (empty($detail[$i]['description'])) 
						$product_count++;
					else
						$print_description = TRUE;

					set_footer($pdf,$y);

					break;
				}
			}

			if (!empty($detail[$i]['description']))
			{
				$html = "
					<table>
						<tr>
							<td style=\"width:".$column_width[0].";\"></td>
							<td colspan = \"4\" style=\"width:1035px;\">".$detail[$i]["description"]."</td>
						</tr>
					</table>";
					
				$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'L', true);

				$y = $pdf->GetY();

				$print_description = FALSE;

				if($y >= 200)
				{
					$product_count = $i;

					if (empty($detail[$i]['description'])) 
						$product_count++;
					else
						$print_description = TRUE;

					set_footer($pdf,$y);

					break;
				}

				$y = $pdf->GetY();
			}

			if($i+1 >= count($detail) && !$print_description)
				$is_finished = TRUE;
			
		}
	}

	$y += $linegap;

	$pdf->writeHTMLCell('', '', $x, $y,'Summary Report Done by : _____________________', 0, 1, 0, true, 'L', true);

	$x = 165;

	$pdf->writeHTMLCell('', '', $x, $y,'Signature : _____________________', 0, 1, 0, true, 'L', true);

	//ob_end_clean();

	$pdf->Output('warehouse_slip.pdf', 'I');
?>