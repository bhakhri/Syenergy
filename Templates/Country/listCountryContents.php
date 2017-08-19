<?php
//This file creates Html Form output in Country Module
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddCountry',300,200);blankValues();return false;" />&nbsp;</td></tr>
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
	

<?php floatingDiv_Start('AddCountry','Add Country'); ?>
<form name="addCountry" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Country Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="countryName" name="countryName" class="inputbox" maxlength="50"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Country Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="countryCode" name="countryCode" class="inputbox" maxlength="5"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Nationality<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="nationalityName" name="nationalityName" class="inputbox" maxlength="20"/></td>
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddCountry');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>


</table>
</form>

<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditCountry','Edit Country'); ?>
<form name="editCountry" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">


    <input type="hidden" name="countryId" id="countryId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Country Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="countryName" name="countryName" class="inputbox"  maxlength="50"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Country Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="countryCode" name="countryCode" class="inputbox" maxlength="5"/></td>
    </tr>
    <tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Nationality<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="nationalityName" name="nationalityName" class="inputbox" maxlength="20"/></td>
</tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"
                    onclick="javascript:hiddenFloatingDiv('EditCountry');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table>
</form>

    <?php floatingDiv_End(); ?>

<?php
//$History: listCountryContents.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/19/10    Time: 3:33p
//Updated in $/LeapCC/Templates/Country
//added print & excel format
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/15/09    Time: 11:13a
//Updated in $/LeapCC/Templates/Country
//special char allowed & format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 4:54p
//Updated in $/LeapCC/Templates/Country
//theme base search button update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/11/09    Time: 1:15p
//Updated in $/LeapCC/Templates/Country
//displayWindow parameter settings (width)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/08/09    Time: 6:04p
//Updated in $/LeapCC/Templates/Country
//country master validation & required fields added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Country
//
//*****************  Version 19  *****************
//User: Arvind       Date: 9/05/08    Time: 5:39p
//Updated in $/Leap/Source/Templates/Country
//removed unsortable class
//
//*****************  Version 18  *****************
//User: Arvind       Date: 8/27/08    Time: 12:18p
//Updated in $/Leap/Source/Templates/Country
//html validated
//
//*****************  Version 17  *****************
//User: Arvind       Date: 8/27/08    Time: 12:08p
//Updated in $/Leap/Source/Templates/Country
//validated HTML
//
//*****************  Version 16  *****************
//User: Arvind       Date: 8/23/08    Time: 11:07a
//Updated in $/Leap/Source/Templates/Country
//modify
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/19/08    Time: 2:47p
//Updated in $/Leap/Source/Templates/Country
//replaced search button
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/Country
//modified the bread crum
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/14/08    Time: 6:56p
//Updated in $/Leap/Source/Templates/Country
//modified the bread crum
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/05/08    Time: 12:47p
//Updated in $/Leap/Source/Templates/Country
//added a new field nationalityName
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/04/08    Time: 6:06p
//Updated in $/Leap/Source/Templates/Country
//removed unneccessary code at the bottom
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/16/08    Time: 6:38p
//Updated in $/Leap/Source/Templates/Country
//a single space between country code and ':' Has benn added
//
//*****************  Version 6  *****************
//User: Arvind       Date: 6/30/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/Country
//modify image button cancel to input type image button
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/25/08    Time: 11:50a
//Updated in $/Leap/Source/Templates/Country
//1) Added a new function for Delete Button
//2) Added blank values function for add button.
//
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/14/08    Time: 7:22p
//Updated in $/Leap/Source/Templates/Country
//modified
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:05p
//Updated in $/Leap/Source/Templates/Country
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:22p
//Created in $/Leap/Source/Templates/Country
//NEw File Added in Country Folder

?>
