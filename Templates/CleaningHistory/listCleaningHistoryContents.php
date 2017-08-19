<?php 
//This file creates Html Form output for cleaning history
//
// Author :Jaineesh
// Created on : 01.05.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
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
					<td valign="top" class="content">
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									<form name="CleaningHistoryForm" action="" method="post" onSubmit="return false;">
										<table width="95%" align="center" border="0" >
											<tr>
												<td>
													<strong>Safaiwala :</strong></td>
												<td>
													<select multiple id="tempEmployee" name="tempEmployee[]" style="vertical-align:middle" size="3" class="selectfield">
														<option value="" selected="selected">ALL</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTempEmployeeName();
														?>
													</select>
												</td>
												<td >
													<strong>Hostel :</strong></td>
												<td>
													<select multiple id="hostel" name="hostel[]" style="vertical-align:middle" size="3" class="selectfield">
														<option value="" selected="selected">ALL</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getHostelName();
														?>
													</select>
												</td>
										
												<td valign="center" ><b>Date Between : </b></td>
												<td valign="center" >
													<?php
													   require_once(BL_PATH.'/HtmlFunctions.inc.php');
													   echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
													?>
													&nbsp;<b>And</b>
													<?php
														   require_once(BL_PATH.'/HtmlFunctions.inc.php');
														   echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
													?>
												</td>
												<td align="center">
													<span style="padding-right:10px" >
													<input type="image" name="cleaningHistory" value="cleaningHistory" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
										</table>
									</form>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Cleaning History :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
											<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											
											<td colspan="2" class="content_title" align="right"><input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
											  <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- form table ends -->
						
					</td>
				</tr>
			</table>
		</table>


<?php 
//$History: listCleaningHistoryContents.php $
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 4/16/10    Time: 3:45p
//Updated in $/LeapCC/Templates/CleaningHistory
//fixed bug nos. 0003278, 0002889, 0003279, 0003271
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 11/16/09   Time: 5:29p
//Updated in $/LeapCC/Templates/CleaningHistory
//fixed bug nos.0002013,0002014
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/13/09    Time: 4:34p
//Updated in $/LeapCC/Templates/CleaningHistory
//fixed bug nos.0000116,0000099,0000117,0000119,0000121,0000097
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/29/09    Time: 3:40p
//Updated in $/LeapCC/Templates/CleaningHistory
//fixed issue nos. 0000180,0000133 
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/10/09    Time: 6:39p
//Updated in $/LeapCC/Templates/CleaningHistory
//modifications in alignment
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:25p
//Updated in $/LeapCC/Templates/CleaningHistory
//change class from selectfield1 to selectfield
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/05/09    Time: 5:05p
//Updated in $/LeapCC/Templates/CleaningHistory
//modification in design 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 4:34p
//Updated in $/LeapCC/Templates/CleaningHistory
//show some fields on list
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:29p
//Created in $/LeapCC/Templates/CleaningHistory
//
?>
