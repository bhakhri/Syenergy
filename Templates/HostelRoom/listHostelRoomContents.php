<?php
//-------------------------------------------------------
// Purpose: to design the layout for hostel room.
//
// Author : Jaineesh
// Created on : (26.06.2008 )
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddHostelRoom',320,250);blankValues();return false;"/>&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddHostelRoom','Add Hostel Room'); ?>
<form name="addHostelRoom" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="32%" class="contenttab_internal_rows">&nbsp;<nobr><b>Room Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td width="68%" class="padding">:&nbsp;<input type="text" id="roomName" name="roomName" class="inputbox" maxlength="50" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="hostelName" id="hostelName" onChange="getRoomType(this.value,'add','');">
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getHostelName($REQUEST_DATA['hostelName']==''? $hostelRoomRecordArray[0]['hostelId'] : $REQUEST_DATA['hostelName'] );
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Room Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="hostelroomtype" id="hostelroomtype" onChange="getRoomDetail();">
			<option value="">Select</option>
			
			</select>
			</td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Room Capacity<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="roomCapacity" name="roomCapacity" readonly=true class="inputbox"  maxlength="5" ></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Room Floor<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="roomFloor" name="roomFloor" class="inputbox"  maxlength="5" ></td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddHostelRoom');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditHostelRoom','Edit Hostel Room'); ?>
<form name="editHostelRoom" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	  <input type="hidden" name="hostelRoomId" id="hostelRoomId" value="" />
		<tr>
			<td width="32%" class="contenttab_internal_rows">&nbsp;<nobr><b>Room Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td width="68%" class="padding">:&nbsp;<input type="text" id="roomName" name="roomName" class="inputbox" value="" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="hostelName" id="hostelName" onChange="getRoomType(this.value,'edit','');">
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getHostelName($REQUEST_DATA['hostelName']==''? $hostelRoomRecordArray[0]['hostelId'] : $REQUEST_DATA['hostelName'] );
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Room Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="hostelroomtype" id="hostelroomtype" onChange="getRoomEditDetail();">
			<option value="">Select</option>
			</select>
			</td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Room Capacity<?php echo REQUIRED_FIELD ?> </b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="roomCapacity" name="roomCapacity" class="inputbox" readonly='true' value="" maxlength="5"></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Room Floor<?php echo REQUIRED_FIELD ?> </b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="roomFloor" name="roomFloor" class="inputbox" value="" maxlength="5"></td>
		</tr>
		<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
			onclick="javascript:hiddenFloatingDiv('EditHostelRoom');return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php 
floatingDiv_End(); 

// $History: listHostelRoomContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/26/09   Time: 4:20p
//Updated in $/LeapCC/Templates/HostelRoom
//done changes to save, edit & show hostel type according to hostel name
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Templates/HostelRoom
//give print & export to excel facility
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:46p
//Updated in $/LeapCC/Templates/HostelRoom
//fixed bug nos.0000622,0000623,0000624,0000611
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/20/09    Time: 3:02p
//Created in $/LeapCC/Templates/HostelRoom
//copy from sc
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 7/16/09    Time: 3:46p
//Updated in $/Leap/Source/Templates/HostelRoom
//fire event on hostel room type at edit
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 7/16/09    Time: 1:22p

//Updated in $/Leap/Source/Templates/HostelRoom
//Put new messages for hostel room type 
//Get capacity & rent by selecting room type
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 6/09/09    Time: 10:29a
//Updated in $/Leap/Source/Templates/HostelRoom
//fixed bug nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313 to 1317
//bugs of cc fixed on sc also
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 6/05/09    Time: 1:07p
//Updated in $/Leap/Source/Templates/HostelRoom
//add new field hostel room type
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 6/01/09    Time: 12:03p
//Updated in $/Leap/Source/Templates/HostelRoom
//put link of hostel room
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/16/09    Time: 2:17p
//Updated in $/Leap/Source/Templates/HostelRoom
//modified left alignment
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Templates/HostelRoom
//make changes for sending sendReq() function
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 1/10/09    Time: 4:14p
//Updated in $/Leap/Source/Templates/HostelRoom
//use required fields & left labels
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/14/08   Time: 5:01p
//Updated in $/Leap/Source/Templates/HostelRoom
//embedded print option
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/25/08    Time: 5:48p
//Updated in $/Leap/Source/Templates/HostelRoom
//fixed bug
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:51p
//Updated in $/Leap/Source/Templates/HostelRoom
//modification in indentation
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:08p
//Updated in $/Leap/Source/Templates/HostelRoom
//modified in indentation
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:48a
//Updated in $/Leap/Source/Templates/HostelRoom
//modified in html
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/23/08    Time: 12:51p
//Updated in $/Leap/Source/Templates/HostelRoom
//modified for messages
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:37p
//Updated in $/Leap/Source/Templates/HostelRoom
//delete height & width of button
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/12/08    Time: 11:15a
//Updated in $/Leap/Source/Templates/HostelRoom
//modified in bread crump
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:34a
//Updated in $/Leap/Source/Templates/HostelRoom
//modification with cancel image button
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/30/08    Time: 2:51p
//Updated in $/Leap/Source/Templates/HostelRoom
//modification with ajax functions
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:35p
//Created in $/Leap/Source/Templates/HostelRoom
//show template of hostel room
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:58p
//Updated in $/Leap/Source/Templates/States
//QA defects fixed and delete code function added
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 6/16/08    Time: 11:13a
//Updated in $/Leap/Source/Templates/States
//Added delete message
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 6/14/08    Time: 1:32p
//Updated in $/Leap/Source/Templates/States
//fixed the defects produced in QA testing and added comments header,
//footer

?>
    <!--End: Div To Edit The Table-->
    


