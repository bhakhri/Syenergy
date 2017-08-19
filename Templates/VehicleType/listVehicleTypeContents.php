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
 require_once(BL_PATH.'/helpMessage.inc.php');    
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddVehicleType',330,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddVehicleType','Add Vehicle Type'); ?>
<form name="addVehicleType" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Type <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="vehicleType" name="vehicleType" class="inputbox" maxlength="30" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Main Tyres<?php echo REQUIRED_FIELD; 
            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
            echo HtmlFunctions::getInstance()->getHelpLink('Main/Spare Tyre',HELP_MAIN_SPARE_TYRE);?> </b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="mainTyres" name="mainTyres" class="inputbox" maxlength="2"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Spare Tyres<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="spareTyres" name="spareTyres" class="inputbox" maxlength="2"></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddVehicleType');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditVehicleType','Edit Vehicle Type'); ?>
<form name="editVehicleType" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="vehicleTypeId" id="vehicleTypeId" value="" />
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Type <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="vehicleType" name="vehicleType" class="inputbox" maxlength="30" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Main Tyres<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="mainTyres" name="mainTyres" class="inputbox" maxlength="2"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Spare Tyres<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="spareTyres" name="spareTyres" class="inputbox" maxlength="2"></td>
		</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:18px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditVehicleType'); return false;" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?>     
<?php 
// $History: listVehicleTypeContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/VehicleType
//fixed bug on fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/24/09   Time: 12:01p
//Updated in $/Leap/Source/Templates/VehicleType
//fixed bug nos. 0002354, 0002353, 0002351, 0002352, 0002350, 0002347,
//0002348, 0002355, 0002349
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/VehicleType
//fixed bug during self testing
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/24/09   Time: 6:06p
//Updated in $/Leap/Source/Templates/VehicleType
//make new master vehicle type
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:47p
//Created in $/Leap/Source/Templates/VehicleType
//new template for vehicle type
//
?>