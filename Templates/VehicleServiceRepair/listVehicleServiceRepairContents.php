<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR VEHICLE SERIVCE REPAIR
//
// Author :Jaineesh
// Created on : (10.06.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
								<a href="listExtendService.php" class='redLink' width="80%">Extended Vehicle Service</a>
								<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="getServiceDetailData();displayWindow('AddServiceRepair',330,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php floatingDiv_Start('AddServiceRepair','Add an Entry for Vehicle Service or Repair'); ?>
<form name="addVehicleServiceRepair" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails()">
			<option value="">Select</option>
			<?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getVehicleTypes();
			?>
			</select>
			</td>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="inputbox1" name="busNo" id="busNo">
				<option value="">Select</option>
				<?php
					//require_once(BL_PATH.'/HtmlFunctions.inc.php');
					//echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="inputbox1" name="busService" id="busService" onchange="getServiceDetail(this.value);">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getVehicleServiceData();
				?></select>
			</td>
		</tr>
		<tr id="getServiceNo" style="display:none">
		<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;&nbsp;<b>Service No.</b></nobr></td>
		<td class="padding">:&nbsp;<select  class="selectfield" id="serviceNo" name="serviceNo">
		<option value="">Select</option>
		<?php
			//require_once(BL_PATH.'/HtmlFunctions.inc.php');
			//echo HtmlFunctions::getInstance()->getHostelRoomData();
			?>
		</select>
		</td>
	</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Date</b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('serviceDate',date('Y-m-d'));
			?>
			</nobr></td>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;KM Reading on Entry<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding"><nobr><b>:&nbsp;</b>
				<input type="text" id="readingEntry" name="readingEntry" class="inputbox" maxlength="10" onkeydown="return sendKeys('workshopAttendees',event,this.form);"/> 
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Bill/Ticket No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
				<input type="text" id="billNo" name="billNo" class="inputbox" maxlength="20" onkeydown="return sendKeys('workshopAttendees',event,this.form);"/></td>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Serviced at<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding"><nobr><b>:&nbsp;</b>
				<input type="text" id="servicedAt" name="servicedAt" class="inputbox" maxlength="100" onkeydown="return sendKeys('workshopAttendees',event,this.form);"/> 
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Details
           <?php  require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                 echo HtmlFunctions::getInstance()->getHelpLink('Service Details',HELP_SERVICE_DETAILS);   ?>
                        </b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
		</tr>
		<tr>
			<td colspan="6"><div id="serviceDetail" style="height:150px;overflow:auto;"></div></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Repair Details
            <?php  require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                  echo HtmlFunctions::getInstance()->getHelpLink('Repair Details',HELP_REPAIR_DETAILS);   ?></b></nobr></td>
			<td width="79%" class="padding"><nobr><b>&nbsp;:</b>
		</tr>
		<tr>
			<td class="padding"  align="left" >
			<select size="1" name="repairType" id="repairType" style="display:none" class="selectfield">
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getRepairTypeData();
			?>
			</td>
		</tr>
		<tr>
			<td colspan="6">
			<div id="repairDetail" style="height:150px;overflow:auto;">
				<table class="padding" width="100%" border="0"  id="anyid1_add">
					<tbody id="anyidBody1_add">
					  <tr class="rowheading">
						<td class="searchhead_text" width="6%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
						<td class="searchhead_text" width="15%" align="left"><nobr><b>Type</b></nobr></td>
						<td class="searchhead_text" width="15%" align="left"><nobr><b>Items</b></nobr></td>
						<td class="searchhead_text" width="15%" align="left"><nobr><b>Charges</b></nobr></td>
						<td class="searchhead_text" width="7%" align="center"><nobr><b>Action</b></nobr></td>
					  </tr>
					</tbody>
				 </table>               
				<div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
					<a href="javascript:addVehicleRepairOneRow(1,'add');" title="Add One Row"><b>+</b></a>
				</div>
			</div></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" colspan="6">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddServiceRepair');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<!--Start Edit Div-->

<?php floatingDiv_Start('EditServiceRepair','Edit Vehicle Service Repair'); ?>
<form name="editVehicleServiceRepair" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="serviceRepairId" id="serviceRepairId" value="" />  
		
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails();" disabled="disabled">
			<option value="">Select</option>
			<?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getVehicleTypes();
			?>
			</select>
			</td>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" disabled="disabled">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="selectfield" name="busService" id="busService" onchange="getServiceDetail(this.value);" disabled="disabled">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getVehicleServiceData();
				?></select>
			</td>
		</tr>
		<tr id="getEditServiceNo" style="display:none">
			<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;&nbsp;<b>Service No.</b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<span id="divServiceNo" ></span></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Date</b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('serviceDate1',date('Y-m-d'));
			?>
			</nobr></td>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;KM Reading on Entry<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding"><nobr><b>:&nbsp;</b>
				<input type="text" id="readingEntry" name="readingEntry" class="inputbox" maxlength="10" onkeydown="return sendKeys('workshopAttendees',event,this.form);"/> 
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Bill/Ticked No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
				<input type="text" id="billNo" name="billNo" class="inputbox" maxlength="20" onkeydown="return sendKeys('workshopAttendees',event,this.form);"/></td>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Serviced at<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding"><nobr><b>:&nbsp;</b>
				<input type="text" id="servicedAt" name="servicedAt" class="inputbox" maxlength="100" onkeydown="return sendKeys('workshopAttendees',event,this.form);"/> 
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Details</b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
		</tr>
		<tr>
			<td colspan="6"><div id="serviceDetail1" style="height:100px;overflow:auto;"></div></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Repair Details</b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
		</tr>
		<tr>
			<td class="padding"  align="left" >
			<select size="1" name="repairType1" id="repairType1" style="display:none" class="selectfield">
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->getRepairTypeData();
			?>
			</td>
		</tr>
		<tr>
			<td colspan="6">
			<div id="repairDetail" style="height:100px;overflow:auto;">
				<table class="padding" width="100%" border="0"  id="anyid1_edit">
					<tbody id="anyidBody1_edit">
					  <tr class="rowheading">
						<td class="searchhead_text" width="6%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
						<td class="searchhead_text" width="15%" align="left"><nobr><b>Type</b></nobr></td>
						<td class="searchhead_text" width="15%" align="left"><nobr><b>Items</b></nobr></td>
						<td class="searchhead_text" width="15%" align="left"><nobr><b>Charges</b></nobr></td>
						<td class="searchhead_text" width="7%" align="center"><nobr><b>Action</b></nobr></td>
					  </tr>
					</tbody>
				 </table>               
				<div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
					<a href="javascript:addVehicleRepairOneRow(1,'edit');" title="Add One Row"><b>+</b></a>
				</div>
			</div></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>

		<tr>
		<td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
		<input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditServiceRepair');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
</table>
</form>
 <?php floatingDiv_End(); ?>
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

<?php 
// $History: $
//
//
?>