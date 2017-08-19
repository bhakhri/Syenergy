<?php 
//This file creates Html Form output for Fine Collection Report
//
// Author :Jaineesh
// Created on : 15.04.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="allDetailsForm" action="" method="post" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
				<tr>
					<td valign="top" class="content">
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
							   <td class="contenttab_border2" align="center">
							     <div style="height:15px"></div>  
							     <?php require_once(TEMPLATES_PATH . "/listFeeFineReportContents.php");?>   
							   </td>
							</tr>
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Student Detail Fine Collection Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentDetailFineReport" value="studentDetailFineReport" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
											
											<!--<td colspan="2" class="content_title" align="right"><input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>-->
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- form table ends -->
						
					</td>
				</tr>
			</table>
			</form>
		</table>


<?php 
//$History: listStudentDetailCollectionFineContents.php $
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/18/09   Time: 6:10p
//Updated in $/LeapCC/Templates/Fine
//fixed bug nos. 0002247, 0002270
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Fine
//resolved issue 0002209,0002208,0002206,0002169,0002148,0002147,0002151,
//0002219,0002095
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/08/09   Time: 10:09a
//Updated in $/LeapCC/Templates/Fine
//remove asterick sign in Date
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Templates/Fine
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Fine
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/07/09    Time: 7:10p
//Updated in $/LeapCC/Templates/Fine
//some modification in code & put approveByUserId in fine_student table
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/07/09    Time: 4:48p
//Updated in $/LeapCC/Templates/Fine
//put new links in menu for fine & changes in bread crum
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/07/09    Time: 2:26p
//Created in $/LeapCC/Templates/Fine
//new file to show student detail fine collection report
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/07/09    Time: 10:31a
//Created in $/LeapCC/Templates/Fine
//new template of fine collection
//
//
?>
