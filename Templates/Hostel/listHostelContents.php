<?php
//-------------------------------------------------------
// Purpose: to design the layout for Hostel.
//
// Author : Jaineesh
// Created on : (26.06.2008 )
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddHostel',320,250);blankValues();return false;" />&nbsp;</td></tr>
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
<?php floatingDiv_Start('AddHostel','Add Hostel'); ?>
<form name="addHostel" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="hostelName" name="hostelName" class="inputbox" maxlength="100" tabindex="1"></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="hostelCode" name="hostelCode" class="inputbox" maxlength="10" tabindex="2"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>No. of Rooms<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="roomTotal" name="roomTotal" class="inputbox"  maxlength="4" tabindex="3">
			</td>
		</tr>
        <tr>
        <td class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding" >:
            <select class="inputbox" name="hostelType" id="hostelType" size="1" tabindex="4">
            <option value="" selected="selected">SELECT</option>
            <?php 
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getHostelType();
            ?>
            </select></nobr>
            </td>
    </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>No. of Floors<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="floorTotal" name="floorTotal" class="inputbox"  maxlength="3" tabindex="5">
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>Total Capacity<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="totalCapacity" name="totalCapacity" class="inputbox"  maxlength="4" tabindex="6">
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>Warden Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="wardenName" name="wardenName" class="inputbox"  tabindex="6">
            </td>
        </tr>
         <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>Warden Contact No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="wardenContactNo" name="wardenContactNo" class="inputbox"  tabindex="6">
            </td>
        </tr>
        
        
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" tabindex="7" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddHostel');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="8" />
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
<?php floatingDiv_Start('EditHostel','Edit Hostel'); ?>
<form name="editHostel" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	  <input type="hidden" name="hostelId" id="hostelId" value="" />
		<tr>
			<td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="hostelName" name="hostelName" class="inputbox" value="" maxlength="100" tabindex="1"></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="hostelCode" name="hostelCode" class="inputbox" value="" maxlength="10" tabindex="2"> </td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>No. of Rooms<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="roomTotal" name="roomTotal" class="inputbox" value="" tabindex="3" maxlength="4">
			</td>
		</tr>
        <tr>
        <td class="contenttab_internal_rows">&nbsp;<nobr><b>Hostel Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding" >:
            <select class="inputbox" name="hostelType" id="hostelType" size="1" tabindex="4">
            <option value="" selected="selected">SELECT</option>
            <?php 
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getHostelType();
            ?>
            </select></nobr>
            </td>
    </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>No. of Floors<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="floorTotal" name="floorTotal" class="inputbox"  maxlength="3" tabindex="5">
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>Total Capacity<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="totalCapacity" name="totalCapacity" class="inputbox"  maxlength="4" tabindex="6">
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>Warden Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="wardenName" name="wardenName" class="inputbox"  tabindex="6">
            </td>
        </tr>
         <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><b>Warden Contact No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding">:&nbsp;<input type="text" id="wardenContactNo" name="wardenContactNo" class="inputbox"  tabindex="6">
            </td>
        </tr>
        
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" tabindex="7"/>
			<input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditHostel');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="8" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php 
floatingDiv_End(); 

// $History: listHostelContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Templates/Hostel
//give print & export to excel facility
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:41p
//Updated in $/LeapCC/Templates/Hostel
//fixed bug nos.0000737, 0000736,0000734,0000735, 0000585, 0000584,
//0000583
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Templates/Hostel
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Templates/Hostel
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 11:15a
//Created in $/LeapCC/Templates/Hostel
//file contains design template & print template made by Jaineesh  &
//modifications by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 19  *****************
//User: Gurkeerat Sidhu     Date: 18/04/09   Time: 5:43p
//Updated in $/Leap/Source/Template/Hostel
//added new fields (floorTotal,hostelType,totalCapacity) 
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 1/16/09    Time: 2:17p
//Updated in $/Leap/Source/Templates/Hostel
//modified left alignment
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Templates/Hostel
//make changes for sending sendReq() function
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 1/10/09    Time: 4:14p
//Updated in $/Leap/Source/Templates/Hostel
//use required fields & left labels
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 10/14/08   Time: 5:01p
//Updated in $/Leap/Source/Templates/Hostel
//embedded print option
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 9/25/08    Time: 4:52p
//Updated in $/Leap/Source/Templates/Hostel
//fixed bug
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/29/08    Time: 3:49p
//Updated in $/Leap/Source/Templates/Hostel
//modification in indentation
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:43p
//Updated in $/Leap/Source/Templates/Hostel
//modification in indentation
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:48a
//Updated in $/Leap/Source/Templates/Hostel
//modified in html
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/23/08    Time: 12:51p
//Updated in $/Leap/Source/Templates/Hostel
//modified for messages
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:37p
//Updated in $/Leap/Source/Templates/Hostel
//delete height & width of button
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/12/08    Time: 11:15a
//Updated in $/Leap/Source/Templates/Hostel
//modified in bread crump
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/11/08    Time: 5:00p
//Updated in $/Leap/Source/Templates/Hostel
//modified to check duplicate records
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/01/08    Time: 3:46p
//Updated in $/Leap/Source/Templates/Hostel
//modified for OnCreate & OnSuccess functions
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/18/08    Time: 4:15p
//Updated in $/Leap/Source/Templates/Hostel
//aligned action button 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:35a
//Updated in $/Leap/Source/Templates/Hostel
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:32a
//Updated in $/Leap/Source/Templates/Hostel
//modification with cancel image button
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:57p
//Updated in $/Leap/Source/Templates/Hostel
//modification with ajax functions
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:32p
//Created in $/Leap/Source/Templates/Hostel
//show the template of hostel
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