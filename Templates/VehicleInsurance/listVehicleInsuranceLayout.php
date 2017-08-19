<?php 
//---------------------------------------------------
//  This File outputs layout fot Notifications Module
//
// Author :Kavish Manjkhola
// Created on : 24-Mar-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
		</td>
	</tr>
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"></td></tr>
									<tr>
										<td class="contenttab_row" colspan="2" valign="top" ><div id="resultDiv"></div></td>
									</tr>
									<tr>
										<td align="right" colspan="2">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
												<tr>
													<td class="content_title" valign="middle" align="right" width="20%">
														<!-- <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
														<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" > -->
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php floatingDiv_Start('PayVehicleInsurance',''); ?>
<form name="PayVehicleInsurance" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
	<td height="5px"></td></tr>
<tr>
<tr> 
	<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Are you sure you want to pay?</strong></nobr></td>
	<td width="65%" class="padding">?</td>
</tr>
<tr>
	<td height="5px"></td>
</tr>
<tr>
	<td align="center" style="padding-right:10px" colspan="4">
	<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/Pay.gif" onClick="return autopay(this.form,'Add');return false;" />
	<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('PayVehicleInsurance');" />
</td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Hostel Facility Information  Div-->
<?php floatingDiv_Start('divPayInsurance','Vehicle Insurance(Autopay)','',''); ?>
<form name="payInsuranceForm" id="payInsuranceForm"  action="" method="post">  
<input type='hidden' id='txtPayId' name='txtPayId' value=''>
<input type='hidden' id='txtDue' name='txtDue' value=''>
<input type='hidden' id='txtPaid' name='txtPaid' value=''>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="100%" align="left" valign="top"><b>Are you sure you want to pay Insurance?</b>
        </td>
    </tr>
	 <tr>    
        <td width="100%" align="left" valign="top">                          
          <div id="trPayResults"></div> 
        </td>
    </tr>
    <tr id="payInsurance">    
       <td width="100%" align="center" valign="top">
         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/pay.gif" onClick="autoPay();return false;" />&nbsp; 
         <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('divPayInsurance'); return false;" />
       </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>  
