<?php
//-------------------------------------------------------
// Purpose: to design the layout for states.
//
// Author : Pushpender Kumar Chauhan
// Created on : (09.06.2008 )
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddState',315,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddState','Add State'); ?>
<form name="addState" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>State Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="stateName" name="stateName" class="inputbox" maxlength="50" /></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>State Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="stateCode" name="stateCode" class="inputbox"  maxlength="5" /></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>Country Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
    <td class="padding">:&nbsp;<select size="1" class="selectfield" name="countries" id="countries">
	<option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['countries']==''? $stateRecordArray[0]['countryId'] : $REQUEST_DATA['countries'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddState');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditState','Edit State '); ?>
<form name="editState" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="stateId" id="stateId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>State Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="stateName" name="stateName" class="inputbox" value="" /></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>State Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:&nbsp;<input type="text" id="stateCode" name="stateCode" class="inputbox" value="" maxlength="5" /></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Country Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="countries" id="countries">
			<option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['countries']==''? $stateRecordArray[0]['countryId'] : $REQUEST_DATA['countries'] );
              ?>
            </select>
        </td>
    </tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditState');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
</table>
</form>
<?php
floatingDiv_End();

// $History: listStateContents.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/19/10    Time: 3:33p
//Updated in $/LeapCC/Templates/States
//added print & excel format
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/08/09   Time: 5:18p
//Updated in $/LeapCC/Templates/States
//resolved issue 1606
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/13/09    Time: 4:34p
//Updated in $/LeapCC/Templates/States
//fixed bug nos.0000116,0000099,0000117,0000119,0000121,0000097
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/13/09    Time: 10:55a
//Updated in $/LeapCC/Templates/States
//fixed bug no.0000066
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/10/09    Time: 5:42p
//Created in $/LeapCC/Templates/States
//copy from sc with modifications
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/03/09    Time: 4:12p
//Updated in $/Leap/Source/Templates/States
//fixed bug nos. 1198 to 1206 of bug4.doc
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 5/21/09    Time: 4:46p
//Updated in $/Leap/Source/Templates/States
//introduced Grouping Feature and had to remove all html code from Result
//Div, now the request goes to server onLoad of html body
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 8/19/08    Time: 7:04p
//Updated in $/Leap/Source/Templates/States
//added image button `search` and html changes as per Firefox HTML
//validator
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 8/14/08    Time: 7:43p
//Updated in $/Leap/Source/Templates/States
//removed Height and Width of Reset button
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/30/08    Time: 7:24p
//Created in $/Leap/Source/Templates/States
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 6/30/08    Time: 12:47p
//Updated in $/Leap/Source/Templates/States
//assigned width to stateName, country, stateCode columns
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 6/27/08    Time: 4:10p
//Updated in $/Leap/Source/Templates/States
//placed sendReq function for sorting in list labels
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 6/26/08    Time: 8:06p
//Updated in $/Leap/Source/Templates/States
//integration of ajax search results
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



