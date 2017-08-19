<?php 
//This file creates output for "ListStudentReports " Module and provides the option for "export to CSV" and "Printout"
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	 $countHeader=count($REQUEST_DATA);
	 $countHeader= $countHeader-3;
 
?>

	<table  <?php if($countHeader > 6){echo "width='1200'"; ob_start();}
	else{echo "width='1000'";ob_start(); } ?> border="0" cellspacing="0" cellpadding="0" >
		<tr align='center' width="90%" >
			<td align='center' ><br /><h3>Address Labels</h3><br />

			</td>
			<td align="right" width="10%">
<div id="printing" style="display;block">
			<?php if($REQUEST_DATA['id']=='excel' || $REQUEST_DATA['id']=='print'){  }else{ ?>
			<input type="image" name="excel"  src="<?php echo IMG_HTTP_PATH;?>/excel1.jpg"  onclick="location.href='<?php echo UI_HTTP_PATH;?>/exportStudentListToCsv.php?<?php echo $querystring;?>&act=excel'" />
			<input type="image" name="print"  src="<?php echo IMG_HTTP_PATH;?>/print.jpg"  onClick="printout()" />
			<?php } ?>
</div>
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="2" class="" height="10">
				
			</td>
		</tr>

	<tr>
	<td colspan="2" align='center'>
 	<table width='800' border="1" align='center' cellspacing="0" cellpadding="0" class="contenttab_border2">
<?php
	$countRows = count($reportRecordArray);
	if($countRows>0) {
		$recordCounter = 0; //variable taken for counting records, to show 2 records in 1 row.
		foreach($reportRecordArray AS $studentRecord){

			if ($recordCounter % 2 == 0) {
				echo '<tr>';
			}

			echo "<td align='left'><table border='0'><tr><td><b>" . strtoupper($studentRecord['firstName']) . "&nbsp;". strtoupper($studentRecord['lastName']) . "</b></td></tr>";

			echo "<tr><td class='reportData'>";
			echo $studentRecord['studentGender'] == 'M' ? "S/o " : "D/o "; 
			echo strtoupper($studentRecord['fatherName'])."</td></tr>";
			echo "<tr><td class='reportData'>" . strtoupper($studentRecord['corrAddress1']) . "</td></tr>";
			echo "<tr><td class='reportData'>" . strtoupper($studentRecord['corrAddress2']) . "</td></tr>";
			echo "<tr><td class='reportData'>" . strtoupper($studentRecord['cityName']) . "-".$studentRecord['corrPinCode']."</td></tr>";
			echo "<tr><td class='reportData'>" . strtoupper($studentRecord['stateCode']) . ",</td></tr>";
			echo "<tr><td class='reportData'>" . strtoupper($studentRecord['corrPhone']) . "</td></tr>";
			echo "</table>";

			$recordCounter++;
			if ($recordCounter % 2 == 0) {
				echo '</tr>';
			}
		}
	}
	else {
		echo '<tr>
			<td valign="top" colspan="2" class="reportData">
				No Record Found	
			</td>
		</tr>';
	}
	?>
	</table>
	</td>
	</tr>
	</table>