<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
				
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="contenttab_border2" colspan="2">
							<form action="" method="POST" name="listForm" id="listForm">
							<table width="350" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>	
								<td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Select Class: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="studentClass" id="studentClass" onChange="autoPopulate(this.value,'subject','Add');" style="width:250px">
								<option value="">Select</option>
								<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getClassData();
								?>
								</select></td>
								<td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Subject Type: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="subjectType" id="subjectType" style="width:150px">
								<option value="">Select</option>
								 
								</select></td> 
								<td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Subject: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="subject" id="subject" style="width:150px">
								<option value="">ALL</option>
								 
								</select></td> 
								<!--td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Group: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="studentGroup" id="studentGroup">
								<option value="">Select</option>
								 
								</select></td-->   
								<td  align="right" style="padding-right:5px">
								<input type="hidden" name="listSubject" value="1">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="showTestGraph(this.form); return false;"/></td>
							</tr>
							<tr>
								<td colspan="4" height="5px"></td>
							</tr>	
							</table>
							</form>
					    </td>
					</tr>
					<tr id='showTitle' style='display:none;'>
						<td class="contenttab_border" height="20" colspan="2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">Student Consolidated Detail: </td>
								 
							</tr>
							</table>
						</td>
					</tr>
					 <tr>
						<td class="contenttab_row" valign="top" colspan="2" align="center"><div id="resultsDiv1">  
						 </div></td>
					 </tr>
					 <tr>
						<td height="10" colspan="2"></td>
					 </tr>
					 <tr id='nameRow2' style='display:none;'>
								<!--td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										</tr>
									</table>
								</td-->
							</tr> 
					<tr id='nameRow3' style='display:none;'>
						<td class="" colspan="2" height="20">
							<table width="40%" border="0" cellspacing="0" cellpadding="0" height="20"  class="" align="right">
								<tr>
									<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" /></td>
								</tr>
							</table>
							<form method="POST" name="addForm"  action="<?php echo UI_HTTP_PATH;?>/imageSave.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="imageDataFrame">
							<input type='hidden' name='imageData' value='' />
							<iframe id="imageDataFrame" name="imageDataFrame" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
						</td>
					</tr>
				</table>
			
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
<?php 
// $History: listTranferredMarksContents.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/01/09    Time: 10:44a
//Updated in $/LeapCC/Templates/StudentReports
//Updated print report
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 4/30/09    Time: 7:03p
//Updated in $/LeapCC/Templates/StudentReports
//Updated with print report
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/30/09    Time: 10:32a
//Updated in $/LeapCC/Templates/StudentReports
//updated formatting
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/22/09    Time: 10:23a
//Created in $/LeapCC/Templates/StudentReports
//intial checkin
?>