<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR GROUP TYPE LISTING 
//
//
// Author :Jaineesh
// Created on : (14.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
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
                          <!--  <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddGroupType',320,250); blankValues(); return false;" />&nbsp;</td>
                               
                               -->
							</tr>
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
<!--Start Add Div-->

<?php floatingDiv_Start('AddGroupType','Add Group Type'); ?>
<form name="addGroupType" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Group Type Name : </b></nobr></td>
			<td width="79%" class="padding"><input type="text" id="groupTypeName" name="groupTypeName" class="inputbox" maxlength="20" tabindex="1"/></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows"><nobr><b>Abbr. : </b></nobr></td>
			<td class="padding"><input type="text" id="groupTypeCode" name="groupTypeCode" class="inputbox" tabindex="2"/></td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" tabindex="3" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddGroupType');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="4"/>
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditGroupType','Edit Group Type'); ?>
<form name="editGroupType" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	   <input type="hidden" name="groupTypeId" id="groupTypeId" value="" />
			<tr>
				<td width="21%" class="contenttab_internal_rows"><nobr><b>Group Type Name : </b></nobr></td>
				<td width="79%" class="padding"><input type="text" id="groupTypeName" name="groupTypeName" class="inputbox" value="" tabindex="1" /></td>
			</tr>
			<tr>    
				<td class="contenttab_internal_rows"><nobr><b>Abbr. : </b></nobr></td>
				<td class="padding"><input type="text" id="groupTypeCode" name="groupTypeCode" class="inputbox" value="" tabindex="2"/></td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" tabindex="3" />
				<img src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
				onclick="javascript:hiddenFloatingDiv('EditGroupType');" tabindex="4" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->

<?php 
// $History: listGroupTypeContents.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/28/09    Time: 11:07a
//Updated in $/LeapCC/Templates/GroupType
//put sendReq function 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/GroupType
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 9/15/08    Time: 6:40p
//Updated in $/Leap/Source/Templates/GroupType
//comment the code for add, edit & delete
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:45p
//Updated in $/Leap/Source/Templates/GroupType
//modification in indentation
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:14p
//Updated in $/Leap/Source/Templates/GroupType
//modification in indentation
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:39a
//Updated in $/Leap/Source/Templates/GroupType
//modified in html
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/19/08    Time: 1:49p
//Updated in $/Leap/Source/Templates/GroupType
//changed search button
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:36p
//Updated in $/Leap/Source/Templates/GroupType
//delete height & width of button
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/12/08    Time: 11:14a
//Updated in $/Leap/Source/Templates/GroupType
//modified in bread crump
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/11/08    Time: 2:53p
//Updated in $/Leap/Source/Templates/GroupType
//modified to check duplicate records
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/17/08    Time: 10:04a
//Updated in $/Leap/Source/Templates/GroupType
//fixed the bug.
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/16/08    Time: 8:18p
//Updated in $/Leap/Source/Templates/GroupType
//bug fixed
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:40a
//Updated in $/Leap/Source/Templates/GroupType
//modification with cancel image button
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/30/08    Time: 10:54a
//Updated in $/Leap/Source/Templates/GroupType
//Modification with ajax functions
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:22p
//Updated in $/Leap/Source/Templates/GroupType
//modified for delete function
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:47p
//Updated in $/Leap/Source/Templates/GroupType
?>