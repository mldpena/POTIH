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

	$pdf = new CI_TCPDF('L', PDF_UNIT, 'FOLIO', true, 'UTF-8', false);

	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->setFontSubsetting(false);

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, 12);

	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pdf->SetAutoPageBreak(TRUE, 0);

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	$font = 'arialbd';
	$font_size = 12;

	$margin_left = 10;
	$margin_right = 120;
	$margin_top = 5;

	$linegap = 6;

	$page_number = 0;
	$is_finished = FALSE;
	$product_count = 0;
	$description_count = 0;
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

			.td-right{
				text-align: right;
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

	$column_width = array("60px","405px","95px","230px","125px","215px","60px");

	while ($is_finished == FALSE) 
	{
		$footer_printed = FALSE;
		$page_number++;
		$x = $margin_left;
		$y = $margin_top;

		$pdf->AddPage();

		$pdf->SetFont($font,'B',16,'','','');

		$pdf->writeHTMLCell('', '', $x, $y,'PICK-UP SUMMARY', 0, 1, 0, true, 'C', true);

		$y+= $linegap * 2;

		$pdf->writeHTMLCell('', '', $x, $y,'No. # '.$reference_number, 0, 1, 0, true, 'L', true);

		$pdf->SetFont($font,'B',$font_size,'','','');

		$pdf->writeHTMLCell('', '', $x, $y - 6,'Page : '.$page_number, 0, 1, 0, true, 'R', true);
		$pdf->writeHTMLCell('', '', $x, $y,'Date : '.date('m-d-Y', strtotime($entry_date)), 0, 1, 0, true, 'R', true);

		$y+= $linegap * 2;

		$html = <<<EOD
				$style
				<table>
					<tr>
						<td style="width:$column_width[5];" class="tdcenter header-border">Customer Name</td>
						<td style="width:$column_width[0];" class="tdcenter header-border">Qty</td>
						<td style="width:$column_width[6];" class="tdcenter header-border">Unit</td>
						<td style="width:$column_width[1];" class="tdcenter header-border">Item Description</td>
						<td style="width:$column_width[3];" class="tdcenter header-border">Remarks</td>
						<td style="width:$column_width[4];" class="tdcenter header-border">WR #</td>
					</tr>
				</table>
EOD;
		$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'C', true);

		$y += 5;

		for ($i = $product_count; $i < count($detail); $i++) 
		{ 
			if (!$print_description) 
			{
				$html = "
					$style
					<table>
						<tr>
							<td style=\"width:".$column_width[5].";\" class=\"tdcenter table-data\">".substr($detail[$i]["customer"],0,29)."</td>
							<td style=\"width:".$column_width[0].";\" class=\"td-right table-data\">".$detail[$i]["quantity"]."</td>
							<td style=\"width:".$column_width[6].";\" class=\"tdcenter table-data\">".$detail[$i]["uom"]."</td>
							<td style=\"width:".$column_width[1].";\" class=\"table-data\">".$detail[$i]["product"]."</td>
							<td style=\"width:".$column_width[3].";\" class=\"tdcenter table-data\">".$detail[$i]["memo"]."</td>
							<td style=\"width:".$column_width[4].";\" class=\"tdcenter table-data\">".$detail[$i]["reference_number"]."</td>
						</tr>
					</table>";

				$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'L', true);

				$y = $pdf->GetY();

				if($y >= 180)
				{
					$product_count = $i;

					if (empty($detail[$i]['description'])) 
						$product_count++;
					else
						$print_description = TRUE;
					
					if(!$footer_printed)
					{
						set_footer($pdf,$y);
						$footer_printed = TRUE;
					}
				}
			}
			
			if($footer_printed && !($i+1 >= count($detail) && !$print_description))
				break;
				
			
			if (!empty($detail[$i]['description']))
			{
				$description_strings = explode("\n", $detail[$i]['description']);

				for ($z=$description_count; $z < count($description_strings); $z++) 
				{ 
					$detail_description = str_replace(array("<br/>","<br>","<br />"), "", $description_strings[$z]);

					$html = "
						$style
						<table>
							<tr>
								<td colspan = \"3\" style=\"width:335px;\" class=\"table-data\"></td>
								<td colspan = \"3\" style=\"width:760px;\" class=\"table-data\">".$detail_description."</td>
							</tr>
						</table>";

					$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'L', true);

					$y = $pdf->GetY();

					$print_description = FALSE;

					if($y >= 180)
					{
						if(!$footer_printed)
						{
							set_footer($pdf,$y + 6);
							$footer_printed = TRUE;
						}
					}
					
					$product_count = $i;

					if (($z + 1) == count($description_strings))
					{
						$description_count = 0;
						$product_count++;
					}
					else
					{
						$description_count = $z + 1;
						$print_description = TRUE;
					}
					
					if($footer_printed && !($i+1 >= count($detail) && !$print_description))
						break;
						
					$y = $pdf->GetY();
				}
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

	$pdf->Output('pickup_summary.pdf', 'I');
?>