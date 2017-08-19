<?php
 echo '<input type="hidden" name="hiddenInstituteId=" id="hiddenInstituteId" value="'.$sessionHandler->getSessionVariable('InstituteId').'" />';
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddRoom',320,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddRoom','Add Room'); ?>
<form name="addRoom" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Room Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="roomName" name="roomName" class="inputbox" maxlength="50" ></td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Room Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="roomAbbreviation" name="roomAbbreviation" class="inputbox" maxlength="10" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>Room Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td class="padding">&nbsp;<select size="1" class="selectfield" name="roomType" id="roomType">
			<option selected="selected" value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getRoomType();
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Building<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td class="padding">&nbsp;<select size="1" class="selectfield" name="building" id="building" onBlur="getBlock();" >
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBuildingData();
			?>
			</select>
			</td>
		</tr>

		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Block<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td class="padding">&nbsp;<select size="1" class="selectfield" name="blockName" id="blockName" >
			<option value="">Select</option>
			<!--<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBlockData($REQUEST_DATA['blockName']==''? $employeeRecordArray[0]['blockId'] : $REQUEST_DATA['blockName'] );
			?>-->
			</select>
			</td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Capacity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="capacity" name="capacity" class="inputbox" maxlength="4"></td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Exam Room Capacity</b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="examCapacity" name="examCapacity" class="inputbox" maxlength="4"></td>
		</tr>
      
        <tr>
            <td width="21%" class="contenttab_internal_rows"><nobr><b>Institute<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
            <td width="79%" class="padding" style="padding-left:8px">
            <select name="instituteId" id="instituteId" class="htmlElement2" style="width:185px" multiple="multiple" size="3">
            <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getEmployeeInstituteForCommonResourcesData();
            ?>
            </select>
            </td>
        </tr>
      
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddRoom');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />   
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
<?php floatingDiv_Start('EditRoom','Edit Room'); ?>
<form name="editRoom" id="editRoom" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<input type="hidden" name="roomId" id="roomId" value="" />
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Room Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="roomName" name="roomName" class="inputbox" maxlength="50" ></td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Room Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="roomAbbreviation" name="roomAbbreviation" class="inputbox" maxlength="10" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>Room Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td class="padding">&nbsp;<select size="1" class="selectfield" name="roomType" id="roomType">
			<option selected="selected" value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getRoomType();
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Building<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td class="padding">&nbsp;<select size="1" class="selectfield" name="building" id="building" onBlur="getEditBlock();" >
			<option value="">Select</option>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBuildingData();
			?>
			</select>
			</td>
		</tr>

		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Block<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td class="padding">&nbsp;<select size="1" class="selectfield" name="blockName" id="blockName" >
			<option value="">Select</option>
			<!--<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getBlockData($REQUEST_DATA['blockName']==''? $employeeRecordArray[0]['blockId'] : $REQUEST_DATA['blockName'] );
			?>-->
			</select>
			</td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Capacity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="capacity" name="capacity" class="inputbox" maxlength="4" ></td>
		</tr>
		<tr>
			<td width="21%" class="contenttab_internal_rows"><nobr><b>Exam Room Capacity</b></nobr></td>
            <td class="padding">:</td>
			<td width="79%" class="padding">&nbsp;<input type="text" id="examCapacity" name="examCapacity" class="inputbox" maxlength="4" ></td>
		
          
        <tr>
            <td width="21%" class="contenttab_internal_rows"><nobr><b>Institute<?php echo REQUIRED_FIELD; ?></b></nobr></td>
            <td class="padding">:</td>
            <td width="79%" class="padding" style="padding-left:8px">
            <select name="instituteId" id="instituteId" class="htmlElement2" style="width:185px" multiple="multiple" size="3">
            <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getEmployeeInstituteForCommonResourcesData();
            ?>
            </select>
            </td>
        </tr>
      
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditRoom');return false;return false;" />
			
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
    <?php
    floatingDiv_End();
    //$History: listRoomContents.php $
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10-01-07   Time: 4:20p
//Updated in $/LeapCC/Templates/Room
//updated with "Miscellaneous" in room type
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Room
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/03/09    Time: 1:35p
//Updated in $/LeapCC/Templates/Room
//Gurkeerat: resolved issue 1357
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 14/08/09   Time: 16:43
//Updated in $/LeapCC/Templates/Room
//Done enhancement in "Room" module---added room and institute mapping so
//that one room can be shared across institutes
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/12/09    Time: 7:27p
//Updated in $/LeapCC/Templates/Room
//fixed bug nos. 0000969, 0000965, 0000962, 0000963, 0000980, 0000950
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/Room
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Templates/Room
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/02/09    Time: 12:19p
//Updated in $/LeapCC/Templates/Room
//give space in exam room capacity field & required fields
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/26/09    Time: 6:10p
//Updated in $/LeapCC/Templates/Room
//fixed bugs No.5,6 bugs-report.doc dated 26.05.09
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Room
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:52p
//Updated in $/Leap/Source/Templates/Room
//embedded print option
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 9/26/08    Time: 3:07p
//Updated in $/Leap/Source/Templates/Room
//new field exam capacity added 
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 9/25/08    Time: 3:29p
//Updated in $/Leap/Source/Templates/Room
//fixed bug
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:56p
//Updated in $/Leap/Source/Templates/Room
//modification in indentation
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:47p
//Updated in $/Leap/Source/Templates/Room
//modified in indentation
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:18a
//Updated in $/Leap/Source/Templates/Room
//modified in html
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/26/08    Time: 11:50a
//Updated in $/Leap/Source/Templates/Room
//modified in name
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/19/08    Time: 11:01a
//Updated in $/Leap/Source/Templates/Room
//modified in search button
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:38p
//Updated in $/Leap/Source/Templates/Room
//delete height & width of button
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/11/08    Time: 7:49p
//Updated in $/Leap/Source/Templates/Room
//modified in bread crump
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/24/08    Time: 1:09p
//Updated in $/Leap/Source/Templates/Room
//modified in action width size
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:31p
//Updated in $/Leap/Source/Templates/Room
//modified for select option in ie
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/18/08    Time: 2:04p
//Updated in $/Leap/Source/Templates/Room
//modified in room template for select
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:11p
//Updated in $/Leap/Source/Templates/Room
//modified for maxlength room name
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/12/08    Time: 11:39a
//Updated in $/Leap/Source/Templates/Room
//modified in block with blockname through blockid
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/03/08    Time: 8:33p
//Updated in $/Leap/Source/Templates/Room
//modifid in table fields
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/02/08    Time: 8:25p
//Updated in $/Leap/Source/Templates/Room
//show the template list for room manager
    ?>
    <!--End: Div To Edit The Table-->