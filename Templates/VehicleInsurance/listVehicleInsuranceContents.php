<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR VEHICLE TYPE LISTING
//
// Author :Jaineesh
// Created on : (24.11.2009)
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddVehicleInsurance',330,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddVehicleInsurance','Add Insurance'); ?>
<form name="addVehicleInsurance" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Company Name <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="insuringCompanyName" name="insuringCompanyName" class="inputbox" maxlength="30" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Detail</b></nobr></td>
			<td class="padding">:&nbsp;<textarea id="insuringCompanyDetails" name="insuringCompanyDetails" maxlength="255" cols="22" rows="3" class="inputbox" onkeyup="return ismaxlength(this)" style="vertical-align:top" ></textarea></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddVehicleInsurance');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditVehicleInsurance','Edit Insurance'); ?>
<form name="editVehicleInsurance" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="insuringCompanyId" id="insuringCompanyId" value="" />
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Company Name <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="insuringCompanyName" name="insuringCompanyName" class="inputbox" maxlength="30" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Detail</b></nobr></td>
			<td class="padding">:&nbsp;<textarea id="insuringCompanyDetails" name="insuringCompanyDetails" cols="22" rows="3" class="inputbox" style="vertical-align:top" maxlength="255" onkeyup="return ismaxlength(this)"></textarea></td>
		</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:18px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditVehicleInsurance'); return false;" />
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
// $History: listVehicleInsuranceContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/VehicleInsurance
//fixed bug on fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/24/09   Time: 12:01p
//Updated in $/Leap/Source/Templates/VehicleInsurance
//fixed bug nos. 0002354, 0002353, 0002351, 0002352, 0002350, 0002347,
//0002348, 0002355, 0002349
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/VehicleInsurance
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:29p
//Created in $/Leap/Source/Templates/VehicleInsurance
//new vehicle for insurance
//
//
?>