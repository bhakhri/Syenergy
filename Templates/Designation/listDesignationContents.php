<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR DESIGNATION LISTING 
//
//
// Author :Jaineesh
// Created on : (13.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddDesignation',320,250);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
							<tr>
                                <td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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

<?php floatingDiv_Start('AddDesignation','Add Designation'); ?>
<form name="addDesignation" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Designation Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding">:&nbsp;<input type="text" id="designationName" name="designationName" class="inputbox" maxlength="100" ></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="designationCode" name="designationCode" class="inputbox" maxlength="10"></td>
		</tr>
        <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Description</b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="description" name="description" class="inputbox" maxlength="10"></td>
        </tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDesignation');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditDesignation','Edit Designation'); ?>
<form name="editDesignation" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="designationId" id="designationId" value="" />
			<tr>
				<td width="21%" class="contenttab_internal_rows"><nobr><b>Designation Name <?php echo REQUIRED_FIELD; ?></b></nobr></td>
				<td width="79%" class="padding">:&nbsp;<input type="text" id="designationName" name="designationName" class="inputbox" value="" maxlength="100"></td>
			</tr>
			<tr>    
				<td class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
				<td class="padding">:&nbsp;<input type="text" id="designationCode" name="designationCode" class="inputbox" value="" maxlength="10" ></td>
			</tr>
            <tr>    
                <td class="contenttab_internal_rows"><nobr><b>Descripton</b></nobr></td>
                <td class="padding">:&nbsp;<input type="text" id="description" name="description" class="inputbox" value="" maxlength="10" ></td>
            </tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditDesignation');return false;" />
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
// $History: listDesignationContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/Designation
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:00p
//Updated in $/LeapCC/Templates/Designation
//put mandatory fields
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/02/09    Time: 2:28p
//Updated in $/LeapCC/Templates/Designation
//put required field in template
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/28/09    Time: 11:07a
//Updated in $/LeapCC/Templates/Designation
//put sendReq function 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Designation
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 10/13/08   Time: 5:46p
//Updated in $/Leap/Source/Templates/Designation
//embedded print option
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:52p
//Updated in $/Leap/Source/Templates/Designation
//embedded print option
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 9/25/08    Time: 4:42p
//Updated in $/Leap/Source/Templates/Designation
//fixed bug
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:35p
//Updated in $/Leap/Source/Templates/Designation
//modified in indentation
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:28p
//Updated in $/Leap/Source/Templates/Designation
//modified in indentation
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:23a
//Updated in $/Leap/Source/Templates/Designation
//modified in html
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/19/08    Time: 11:10a
//Updated in $/Leap/Source/Templates/Designation
//modified in search button
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/14/08    Time: 6:12p
//Updated in $/Leap/Source/Templates/Designation
//modified for cancel button remove height & width
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/12/08    Time: 10:25a
//Updated in $/Leap/Source/Templates/Designation
//modified in bread crump
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:53p
//Updated in $/Leap/Source/Templates/Designation
//change alert in messagebox
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/17/08    Time: 10:10a
//Updated in $/Leap/Source/Templates/Designation
//fixed the bug.
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:42a
//Updated in $/Leap/Source/Templates/Designation
//modification with cancel image button
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/30/08    Time: 9:43a
//Updated in $/Leap/Source/Templates/Designation
//Give the designation template 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:27p
//Updated in $/Leap/Source/Templates/Designation
//giving title name delete
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/25/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/Designation
//modified with some coding error
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/19/08    Time: 2:44p
//Updated in $/Leap/Source/Templates/Designation
?>