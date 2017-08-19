<?php 
//--------------------------------------------------------
//This file creates Html Form output for marks not entered report
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
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
									<form name="roleWiseUserForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Choose Role :</strong> &nbsp;
												</td>
												<td align="left">
													<select size="1" class="htmlElement" name="roleId" id="roleId" onchange="changeStatus();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getRoleData();?>
													</select>
												</td>
												<td align="center" colspan="4" >
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
										</table>
									</form>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="resultdata">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title" >Role Wise User Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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

<?php floatingDiv_Start('divUserRole','Login Detail '); 
echo UtilityManager::includeJS("swfobject.js");
?>
<form name="UserRoleForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="100%" align="center">
		<input type="hidden" id="userId">
		<select id="roleView" size="1" class="selectfield1" onchange="selectOption(this.value);">
					<!--<option value="">Select</option>-->
					<option value="3">3 months</option>
					<option value="6">6 months</option>
					<option value="9">9 months</option>
					<option value="1">1 year</option>
					
		</select>
			<div id="userRoleInfo" style="height:400px;width:900px;overflow:auto" >
			</div>
			
		</td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>

<?php 
//$History: listRoleWiseUserReport.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/28/09    Time: 4:51p
//Created in $/LeapCC/Templates/StudentReports
//copy from sc
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 4/15/09    Time: 3:58p
//Updated in $/Leap/Source/Templates/ScStudentReports
//modified in showing the graph
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:17p
//Updated in $/Leap/Source/Templates/ScStudentReports
//modified in feedback label & role wise graph
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/10/09    Time: 6:49p
//Updated in $/Leap/Source/Templates/ScStudentReports
//modified the files to show graphs quartely wise
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/22/09    Time: 10:12a
//Created in $/Leap/Source/Templates/ScStudentReports
//file is used to show template of role wise user report
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/19/08    Time: 7:55p
//Created in $/Leap/Source/Templates/ScStudentReports
//file added for "marks not entered report"
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:54p
//Updated in $/Leap/Source/Templates/StudentReports
//improved design and made it working for IE
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/05/08    Time: 1:25p
//Updated in $/Leap/Source/Templates/StudentReports
//done minor modifications
//
?>
