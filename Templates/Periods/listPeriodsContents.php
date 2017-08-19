   <?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR PERIOD LISTING 
//
//
// Author :Jaineesh
// Created on : (14.06.2008)
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddPeriods',330,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddPeriods','Add Periods'); ?>
<form name="addPeriods" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		
		<tr>
			<td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Slot </b></nobr></td>
			<td width="79%" class="padding">:&nbsp;
				<select size="1" name="slotName" id="slotName" >
					<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getPeriodSlot($REQUEST_DATA['slotName']==''? $periodsRecordArray[0]['periodSlotId'] : $REQUEST_DATA['slotName']);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Period Number<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td width="79%" class="padding">:&nbsp;&nbsp;<input type="text" id="periodNumber" name="periodNumber" class="inputbox" style="width:150px" maxlength="4"></td>
		</tr>
		
		<tr>    
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Start Time <?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding"><nobr>:&nbsp;&nbsp;<input type="text" id="startTime" name="startTime" class="inputbox" style="width:150px" maxlength="5"/>
			<select size="1" name="startAmPm" class="selectfield" id="startAmPm" style="width:45px">
			<option selected="selected" value="">Sel</option>
			<option value="AM" width="10%">AM</option>
			<option value="PM">PM</option>
			</select></nobr>
			</td>
		</tr>
		<tr>   
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>End Time<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding"><nobr>:&nbsp;&nbsp;<input type="text" id="endTime" name="endTime" class="inputbox" style="width:150px" maxlength="5"/>
			<select size="1" name="endAmPm" class="selectfield" id="endAmPm" style="width:45px">
			<option selected="selected" value="">Sel</option>
			<option value="AM" >AM</option>
			<option value="PM" >PM</option>
			</select></nobr>
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddPeriods');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditPeriods','Edit Periods'); ?>
<form name="editPeriods" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<input type="hidden" name="periodId" id="periodId" value="" />
		
		<tr>
			<td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Slot</b></nobr></td>
			<td width="79%" class="padding">:&nbsp;
				<select size="1" name="slotName" id="slotName" class="inputbox1">
					<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getPeriodSlot($REQUEST_DATA['slotName']==''? $periodsRecordArray[0]['periodSlotId'] : $REQUEST_DATA['slotName']);
					?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Period Number<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td width="79%" class="padding">:&nbsp;&nbsp;<input type="text" id="periodNumber" name="periodNumber" class="inputbox" 
			style="width:150px" value="" maxlength="4"/></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows"><nobr><b>Start Time<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<input type="text" id="startTime" name="startTime" class="inputbox" value="" 
			style="width:150px" maxlength="5" />
			<select size="1" name="startAmPm" class="selectfield" id="startAmPm" style="width:45px" >
			<option selected="selected" value="">Sel</option>
			<option value="AM" >AM</option>
			<option value="PM" >PM</option>
			</select>
			</td>
		</tr>

		<tr>    
			<td class="contenttab_internal_rows"><nobr><b>End Time<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<input type="text" id="endTime" name="endTime" class="inputbox" value="" style="width:150px" maxlength="5" />
			<select size="1" name="endAmPm" class="selectfield" id="endAmPm" style="width:45px">
			<option selected="selected" value="">Sel</option>
			<option value="AM">AM</option>
			<option value="PM">PM</option>
			</select>
			</td>
		</tr>

		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
			onclick="javascript:hiddenFloatingDiv('EditPeriods');return false;" />
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
    // $History: listPeriodsContents.php $
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 4/20/10    Time: 5:55p
//Updated in $/LeapCC/Templates/Periods
//fixed bug nos. 0003312, 0003311, 0003298, 0003299
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/01/09   Time: 6:52p
//Updated in $/LeapCC/Templates/Periods
//fixed bug no.0001673
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:46p
//Updated in $/LeapCC/Templates/Periods
//fixed bug nos.0001611, 0001632, 0001612, 0001600, 0001599, 0001598,
//0001594
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 12:57p
//Updated in $/LeapCC/Templates/Periods
//resolved issue 1623
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/Periods
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/22/09    Time: 12:28p
//Updated in $/LeapCC/Templates/Periods
//fixed bug no.0000597 & put new message
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/16/09    Time: 3:48p
//Updated in $/LeapCC/Templates/Periods
//fixed bug no.0000599
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/16/09    Time: 11:07a
//Updated in $/LeapCC/Templates/Periods
//bug fixed no. 0000074 of mantis
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/10/09    Time: 2:43p
//Updated in $/LeapCC/Templates/Periods
//modified to save only selected am, pm
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/08/09    Time: 2:46p
//Updated in $/LeapCC/Templates/Periods
//remove mandatory fields on start time & end time
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:40p
//Created in $/LeapCC/Templates/Periods
//get existing period files in leap
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:20p
//Updated in $/Leap/Source/Templates/Periods
//modified to get slot name
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 11/06/08   Time: 6:08p
//Updated in $/Leap/Source/Templates/Periods
//modified in name arrow-none.gif 
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 10/30/08   Time: 11:25a
//Updated in $/Leap/Source/Templates/Periods
//modified
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 10/25/08   Time: 5:43p
//Updated in $/Leap/Source/Templates/Periods
//add new field time table label Id
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 10/14/08   Time: 5:00p
//Updated in $/Leap/Source/Templates/Periods
//embedded print option
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 9/25/08    Time: 4:45p
//Updated in $/Leap/Source/Templates/Periods
//fixed bug
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:54p
//Updated in $/Leap/Source/Templates/Periods
//modification in indentation
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 8/28/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Periods
//modified in indentation
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:17a
//Updated in $/Leap/Source/Templates/Periods
//modified in html
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 8/26/08    Time: 5:38p
//Updated in $/Leap/Source/Templates/Periods
//change search button
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:40p
//Updated in $/Leap/Source/Templates/Periods
//delete height & width of button
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 7/28/08    Time: 7:39p
//Updated in $/Leap/Source/Templates/Periods
//modified for institute id
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:38p
//Updated in $/Leap/Source/Templates/Periods
//select in institute name for i.e.
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 7/17/08    Time: 6:53p
//Updated in $/Leap/Source/Templates/Periods
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 7/15/08    Time: 2:54p
//Updated in $/Leap/Source/Templates/Periods
//concate time + AM or PM
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 7/05/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Periods
//modified for add,edit & delete
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:56p
//Updated in $/Leap/Source/Templates/Periods
//modified in table fields
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:31a
//Updated in $/Leap/Source/Templates/Periods
//modification with cancel image
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:14p
//Updated in $/Leap/Source/Templates/Periods
//modification with ajax functions
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:27p
//Updated in $/Leap/Source/Templates/Periods
//giving title delete
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/25/08    Time: 1:24p
//Updated in $/Leap/Source/Templates/Periods
//modified in design & coding for delete function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:25p
//Updated in $/Leap/Source/Templates/Periods
?>