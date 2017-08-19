<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR AttendanceSet LISTING
// Author :Dipanjan Bhattacharjee
// Created on : (28.12.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------


require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
	<tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddAttendanceSet',315,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
							<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											 <td class="content_title" valign="middle" align="right" width="20%">
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
											 </td>
										</tr>
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
<?php floatingDiv_Start('AddAttendanceSet','',1); ?>
    <form name="AttendanceSet" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="setId" id="setId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Set Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="padding">
         <input type="text" id="setName" name="setName" class="inputbox" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Criteria<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="contenttab_internal_rows">
         <input type="radio" name="setCondition" value="1" checked="checked">Percentages&nbsp;
         <input type="radio" name="setCondition" value="0" >Slabs
        </td>
    </tr>
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddAttendanceSet');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->


<?php
// $History: listAttendanceSetContents.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/28/10    Time: 11:13a
//Updated in $/LeapCC/Templates/AttendanceSet
//attendanceSet limit 50 char updated
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:39
//Created in $/LeapCC/Templates/AttendanceSet
//Added  "Attendance Set Module"
?>