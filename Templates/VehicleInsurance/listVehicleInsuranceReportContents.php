<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Vehicle Report
//
//
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
require_once(TEMPLATES_PATH . "/breadCrumb.php");  
?>

	<tr>
		<td valign="top" colspan=2>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<form action="" method="POST" name="listForm" id="listForm">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
						   <td class="contenttab_border2" >
						     <table border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
									<td class="contenttab_internal_rows"><nobr><b>Select Vehicle Type : </b></nobr></td>
									<td class="padding"><select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails();clearText();">
									<option value="">Select</option>
									<?php
									  require_once(BL_PATH.'/HtmlFunctions.inc.php');
									  echo HtmlFunctions::getInstance()->getVehicleTypes();
									?>
									</select>
									</td>
								
									<td class="contenttab_internal_rows"><nobr><b>Registration No. :</b></nobr></td>
									<td class="padding">&nbsp;<select size="1" class="selectfield" name="vehicleNo" id="vehicleNo" onchange="clearText();">
									<option value="">Select</option>
									<!--<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->getBlockData($REQUEST_DATA['blockName']==''? $employeeRecordArray[0]['blockId'] : $REQUEST_DATA['blockName'] );
									?>-->
									</select>
									</td>
																	 
									<td  align="right" style="padding-right:5px">
									<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getData();return false;" /></td>
								</tr>
								</table>
							  </td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Vehicle Insurance List :</td>
											<!--<td colspan="1" class="content_title" align="right"><input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>-->
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
								</td>
							</tr>
							<tr id='printRow' style='display:none;'><td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();return false;" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td></tr>
						</table>
					  </form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


<?php
// $History: listVehicleReportContents.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/29/10    Time: 5:19p
//Updated in $/Leap/Source/Templates/Vehicle
//change the breadcrumb
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>