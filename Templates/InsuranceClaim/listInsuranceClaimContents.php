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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick= "displayWindow('AddInsuranceClaim',330,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
							<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										
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

<?php floatingDiv_Start('AddInsuranceClaim','Add Insurance Claim'); ?>
<form name="addInsuranceClaim" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
		  <td valign="top">
		    <table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails()">
					<option value="">Select</option>
					<?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getVehicleTypes();
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" onchange="getNcbDetails();" >
					<option value="">Select</option>
					<?php
						//require_once(BL_PATH.'/HtmlFunctions.inc.php');
						//echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Claim Date</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('claimDate',date('Y-m-d'));?>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Claim Amount<?php echo REQUIRED_FIELD; 
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                   echo HtmlFunctions::getInstance()->getHelpLink('ClaimAmount',HELP_INSURANCE_CLAIM_AMOUNT);?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="claimAmount" name="claimAmount" class="inputbox" maxlength="10" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Total Expenses<?php echo REQUIRED_FIELD; 
                   require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                   echo HtmlFunctions::getInstance()->getHelpLink('Total Expenses',HELP_VEHICLE_EXPENSES);?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="totalExpenses" name="totalExpenses" class="inputbox" maxlength="10" onblur="getSelfExpenses();" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Self expenses borne by us<?php echo REQUIRED_FIELD; 
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                    echo HtmlFunctions::getInstance()->getHelpLink('Self Expenses born by us',HELP_VEHICLE_SELF_EXPENSES);?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="selfExpenses" name="selfExpenses" class="inputbox" maxlength="10" readonly="readonly" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;NCB as on Date of Claim<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="ncbClaim" name="ncbClaim" class="inputbox" maxlength="10" readonly="readonly"></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Logging Claim<?php echo REQUIRED_FIELD; 
                   require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                   echo HtmlFunctions::getInstance()->getHelpLink('Logging Claim',HELP_LOGGING_CLAIM);?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="loggingClaim" name="loggingClaim" class="inputbox" maxlength="10" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date of Settlement</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('settlementDate',date('Y-m-d'));?>
					</td>
				</tr>
			</table>
		</td>
		</tr>
		
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddInsuranceClaim');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditInsuranceClaim','Edit Insurance Claim'); ?>
<form name="editInsuranceClaim" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="claimId" id="claimId" value="" />
		<input type="hidden" name="vehicleTypeId" id="vehicleTypeId" value="" />
		<tr>
		  <td valign="top">
		    <table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getEditVehicleDetails('xx');">
					<option value="">Select</option>
					<?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getVehicleTypes();
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" onchange="getEditNcbDetails();" >
					<option value="">Select</option>
					<?php
						//require_once(BL_PATH.'/HtmlFunctions.inc.php');
						//echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Claim Date</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('claimDate1',date('Y-m-d'));?>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Claim Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="claimAmount" name="claimAmount" class="inputbox" maxlength="10" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Total Expenses<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="totalExpenses" name="totalExpenses" class="inputbox" maxlength="10" onblur="getEditSelfExpenses();" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Self expenses borne by us<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="selfExpenses" name="selfExpenses" class="inputbox" maxlength="10" readonly="readonly" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;NCB as on Date of Claim<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="ncbClaim" name="ncbClaim" class="inputbox" maxlength="10" readonly="readonly"></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Logging Claim<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="loggingClaim" name="loggingClaim" class="inputbox" maxlength="10" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date of Settlement</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('settlementDate1',date('Y-m-d'));?>
					</td>
				</tr>
			</table>
		</td>
		</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:18px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditInsuranceClaim'); return false;" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
    <!-- Help Div Starts -->
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
 <!-- Help Div Ends -->    
<?php 
// $History: listInsuranceClaimContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 2/10/10    Time: 2:38p
//Updated in $/Leap/Source/Templates/InsuranceClaim
//fixed bug nos.0002836, 0002835, 0002802, 0002801
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 2/06/10    Time: 7:25p
//Updated in $/Leap/Source/Templates/InsuranceClaim
//fixed bug nos. 0002805, 0002809
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/06/10    Time: 12:16p
//Updated in $/Leap/Source/Templates/InsuranceClaim
//fixed bug nos. 0002793, 0002796, 0002797, 0002795, 0002798
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:45a
//Created in $/Leap/Source/Templates/InsuranceClaim
//add template file for vehicle insurance claim
//
//
?>