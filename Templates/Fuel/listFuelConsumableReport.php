<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
        </td>
    </tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" >
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" class="content" >
										<form name="allDetailsForm" method="post"  action="" onsubmit="return false;">
											<table cellpadding="0" cellspacing="0" border="0" width="100%" valign="middle">
												<tr>
													<td class="contenttab_border" height="20">
														<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
															<tr>
																<td class="content_title">Fuel Consumable Report : </td>
																<td class="content_title">&nbsp;</td>
															</tr>
														</table>
													</td>
													</tr>
													<tr>
													<td class="contenttab_border2">
														<table border="0" cellpadding="0" cellspacing="0" valign="top" >
															<tr>
																<td height="5px"></td>
															</tr>
															<tr>
																<td width="5px"></td>
																<td class="contenttab_internal_rows" valign="center"><b>From &nbsp;:</b>
																<td class="padding" valign="center">&nbsp;
																	<?php
																		require_once(BL_PATH.'/HtmlFunctions.inc.php');
																		echo HtmlFunctions::getInstance()->datePicker('fromDate',date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-30, date('Y'))));
																	?>
																</td>
																<td class="contenttab_internal_rows" valign="center"><b>To &nbsp;:</b>
																<td class="padding" valign="center">&nbsp;
																	<?php
																		require_once(BL_PATH.'/HtmlFunctions.inc.php');
																		echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
																	?>
																</td>
																<td class="contenttab_internal_rows" valign="center"><b>Vehicle Type &nbsp;:</b>
																<td class="padding" valign="center">&nbsp;
																	<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails();clearList();">
																	<option value="">Select</option>
																		<?php
																			require_once(BL_PATH.'/HtmlFunctions.inc.php');
																			echo HtmlFunctions::getInstance()->getVehicleTypes();
																		?>
																	</select>
																</td>
																<td class="contenttab_internal_rows" valign="center" rowspan=
																"2"><nobr><b>Vehicle No. :</b></nobr></td>
																<td class="contenttab_internal_rows" valign="center"rowspan=
																"2">&nbsp;<select multiple="multiple" size="5" name="vehicleNo[]" id="vehicleNo" onchange="clearList();" style="width:150px;">
																</select><br/>
																Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("vehicleNo[]","All","allDetailsForm");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("vehicleNo[]","None","allDetailsForm");'>None</a>
																</td>
																<td width="2px"></td>
																<td valign="bottom" style="padding-bottom:12px;">
																	<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getData();return false;"/>
																</td>
															</tr>
															<tr><tr><td></td></tr>
													</table>
												</td>
											</tr>
									</table>

									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr id='nameRow' style='display:none;'>
											<td class="" height="20">
												<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
													<tr>
														<td colspan="1" class="content_title">Vehicle List :</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr id='resultRow' style='display:none;'>
											<td colspan='1' class='contenttab_row'>
												<div id = 'resultsDiv' style="overflow:auto;height='100px'"></div>
											</td>
										</tr>
										<tr id='printRow' style='display:none;'>
										<td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();return false;" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td></tr>
									</table>
								   </form>
								</td>
							</tr>
							<tr><td height="10px"></td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
    </tr>
    </table>

<?php
// $History: listFuelReportContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/09/09   Time: 18:11
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing found in self testing
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 28/07/09   Time: 11:10
//Updated in $/Leap/Source/Templates/Fuel
//corrected button's position
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/07/09   Time: 19:00
//Updated in $/Leap/Source/Templates/Fuel
//Updated fuel usage report by adding "fuel usage average details"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 11:46
//Created in $/Leap/Source/Templates/Fuel
//Added new files for Bus Masters
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:19
//Updated in $/SnS/Templates/Fuel
//Modified label
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:39
//Updated in $/SnS/Templates/Fuel
//Removed Extra html code
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 10:55
//Created in $/SnS/Templates/Fuel
//Added "Fuel Uses Report" module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:36
//Updated in $/SnS/Templates/Fuel
//Enhanced fuel master
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:04
//Updated in $/SnS/Templates/Fuel
//Enhanced fuel master
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:50
//Updated in $/SnS/Templates/Fuel
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:37
//Created in $/SnS/Templates/Fuel
//Created Fuel Master
?>