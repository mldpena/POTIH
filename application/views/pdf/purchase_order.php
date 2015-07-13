<?php
	ini_set('memory_limit', '128M');

	function set_footer($pdf,$y)
	{
		$y += 6;
		$x = 10;

		$pdf->writeHTMLCell('', '', $x, $y,'Checker : _____________________', 0, 1, 0, true, 'L', true);

		$x = 115;

		$pdf->writeHTMLCell('', '', $x, $y,'Counter Checker : _____________________', 0, 1, 0, true, 'L', true);
	}

	//ob_start();

	$pdf = new TCPDF('P', PDF_UNIT, 'FOLIO', true, 'UTF-8', false);

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

	$half_page_y = 164;
	$whole_page_y = 329;

	$linegap = 6;

	$is_finished = FALSE;
	$page_number = 0;
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

	$column_width = array("55px","430px","95px","120px");

	while ($is_finished == FALSE) 
	{
		$page_number++;

		$x = $margin_left;

		if ($page_number % 2 == 0 )
			$y = $half_page_y + $margin_top;
		else
		{
			$pdf->AddPage();
			$y = $margin_top;
		}

		$pdf->SetFont($font,'B',20,'','','');

		$pdf->writeHTMLCell('', '', $x, $y,'PURCHASE ORDER', 0, 1, 0, true, 'C', true);

		$y+= $linegap * 2;

		$pdf->writeHTMLCell('', '', $x, $y,'No. # '.$reference_number, 0, 1, 0, true, 'L', true);

		$pdf->SetFont($font,'B',$font_size,'','','');

		$pdf->writeHTMLCell('', '', $x, $y,'Date : '.date("M d, Y",strtotime($entry_date)), 0, 1, 0, true, 'R', true);

		$y+= $linegap * 2;

		$pdf->writeHTMLCell('', '', $x, $y,'Supplier : '.$supplier, 0, 1, 0, true, 'L', true);

		$pdf->writeHTMLCell('', '', $x, $y,'Order For : '.$for_branch, 0, 1, 0, true, 'R', true);

		$y+= $linegap + 4;

		$pdf->writeHTMLCell('', '', $x, $y,'Memo : '.$memo, 0, 1, 0, true, 'L', true);

		$y+= $linegap + 4;

		$html = <<<EOD
				$style
				<table>
					<tr>
						<td style="width:$column_width[0];" class="tdleft">Qty</td>
						<td style="width:$column_width[1];">Item Description</td>
						<td style="width:$column_width[2];" class="tdcenter">Item Code</td>
						<td style="width:$column_width[3];" class="tdcenter">Remarks</td>
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
					$style
					<table>
						<tr>
							<td style=\"width:".$column_width[0].";\">".$detail[$i]["quantity"]."</td>
							<td style=\"width:".$column_width[1].";\">".$detail[$i]["product"]."</td>
							<td style=\"width:".$column_width[2].";\" class=\"tdcenter\">".$detail[$i]["item_code"]."</td>
							<td style=\"width:".$column_width[3].";\" class=\"tdcenter\">".$detail[$i]["memo"]."</td>
						</tr>
					</table>";
				$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'L', true);

				$y = $pdf->GetY();

				if($y+40 >= $half_page_y && $page_number % 2 != 0 )
				{
		            $product_count = $i;

		            if (empty($detail[$i]['description'])) 
		            	$product_count++;
		            else
		            	$print_description = TRUE;

		           	set_footer($pdf,$y);

		            break;
		        }
		        else if($y+35 >= $whole_page_y && $page_number % 2 == 0 )
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
					$style
					<table>
						<tr>
							<td style=\"width:".$column_width[0].";\"></td>
							<td colspan = \"3\" style=\"width:645px;\">".$detail[$i]["description"]."</td>
						</tr>
					</table>";
					
				$pdf->writeHTMLCell('', '', $x, $y, $html, 0, 1, 0, true, 'L', true);

				$y = $pdf->GetY();

				$print_description = FALSE;

				if($y+40 >= $half_page_y && $page_number % 2 != 0 )
				{
		            $product_count = $i+1;
					set_footer($pdf,$y);
		            break;
		        }
		        else if($y+35 >= $whole_page_y && $page_number % 2 == 0 )
		        {
		            $product_count = $i+1;
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

	$pdf->writeHTMLCell('', '', $x, $y,'Checker : _____________________', 0, 1, 0, true, 'L', true);

	$x = 115;

	$pdf->writeHTMLCell('', '', $x, $y,'Counter Checker : _____________________', 0, 1, 0, true, 'L', true);

	//ob_end_clean();

	$pdf->Output('purchase_order.pdf', 'I');
?>