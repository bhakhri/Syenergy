<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TRAINING
//
//
// Author :Jaineesh
// Created on : (23.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content" >
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('ReportComplaintDiv',340,450);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="ReportComplaintResultDiv"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										
                    </table>
                </td>
              </tr>
            </table>
          </td>
       </tr>
    </table>
   </td>
  </tr>
</table>    
<!--Start Add/Edit Div-->
<?php floatingDiv_Start('ReportComplaintDiv',''); ?>
<form name="ReportComplaint" action="" method="post">  
<input type="hidden" name="complaintId" id="complaintId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Complaint Status</b></nobr></td>
		<td class="padding">:&nbsp;<select  class="selectfield" id="complaintStatus" name="complaintStatus" onchange="checkStatus();">
		<option value="1">Pending</option>
		<!--<option value="2">Escalate</option>-->
		<option value="3">Complete</option>
		</select></td>
	</tr>
	<tr> 
		<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Tracking Number</strong></nobr></td>
		<td width="65%" class="padding">:&nbsp;<input type="text" id="trackingNumber" name="trackingNumber" class="inputbox" disabled="disabled" onblur="getValues();"></td>
	</tr>
	<tr> 
		<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Subject<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
		<td width="65%" class="padding">:&nbsp;<input type="text" id="subject" name="subject" class="inputbox" maxlength="255">
		</td>
	</tr>
	<tr> 
		<td width="35%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Description<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
		<td width="65%" class="padding">:
			<textarea id="description" name="description" cols="22" rows="3" class="inputbox" style="vertical-align:top"></textarea>
		</td>
	</tr>
	<tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Category<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
      <td class="padding">:&nbsp;<select size="1" class="selectfield" name="category" id="category" >
		<option value="">Select</option>
		<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getComplaintCategoryData();
		?></select>
	 </td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Hostel<?php echo REQUIRED_FIELD; ?></b></nobr></td>
		<td class="padding">:&nbsp;<select  class="selectfield" id="hostel" name="hostel" onblur="getHostelRoom();">
		<option value="">Select</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getHostelName();
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Room<?php echo REQUIRED_FIELD; ?></b></nobr></td>
		<td class="padding">:&nbsp;<select  class="selectfield" id="room" name="room">
		<option value="">Select</option>
		<?php
			//require_once(BL_PATH.'/HtmlFunctions.inc.php');
			//echo HtmlFunctions::getInstance()->getHostelRoomData();
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Reported By<?php echo REQUIRED_FIELD; ?></b></nobr></td>
		<td class="padding">:
		<select  class="selectfield" id="reportedBy" name="reportedBy">
			<option value="">Select</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getStudentHostelData();
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Complaint on</strong></nobr></td>
		<td class="padding">:&nbsp;<?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
		?>
		</td>
	</tr>
	
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;"  />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ReportComplaintDiv');if(flag==true){getReportComplaintData();flag=false;}return false;" />
    </td>
	</tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<?php
// $History: listReportComplaintsContents.php $
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/ReportComplaints
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/01/09    Time: 7:23p
//Updated in $/LeapCC/Templates/ReportComplaints
//fixed bug nos.0001374, 0001375, 0001376, 0001379, 0001373
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/02/09    Time: 6:22p
//Updated in $/LeapCC/Templates/ReportComplaints
//fixed bugs nos.0000193,0000194,0000359
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Templates/ReportComplaints
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/22/09    Time: 3:03p
//Updated in $/LeapCC/Templates/ReportComplaints
//fixed bug nos.0000193, 0000192,0000190,0000194
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:34p
//Updated in $/LeapCC/Templates/ReportComplaints
//bugs fixed nos. 1370 to 1380 of Issues [08-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/ReportComplaints
//make the changes as per discussion with pushpender sir
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:18p
//Created in $/LeapCC/Templates/ReportComplaints
//
?>