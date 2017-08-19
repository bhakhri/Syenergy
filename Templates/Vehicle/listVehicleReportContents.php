<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Vehicle Report
//
//
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<form action="" method="POST" name="listForm" id="listForm">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
						   <td class="contenttab_border2" >
						     <table border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
									<td class="contenttab_internal_rows"><nobr><b>Select Vehicle Type : </b></nobr></td>
									<td class="padding"><select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails();clearText();">
									<option value="">Select</option>
									<?php
									  require_once(BL_PATH.'/HtmlFunctions.inc.php');
									  echo HtmlFunctions::getInstance()->getVehicleTypes();
									?>
									</select>
									</td>

									<td class="contenttab_internal_rows"><nobr><b>Registration No. :</b></nobr></td>
									<td class="padding">&nbsp;<select size="1" class="selectfield" name="vehicleNo" id="vehicleNo" onchange="clearText();" >
									<option value="">Select</option>
									<!--<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->getBlockData($REQUEST_DATA['blockName']==''? $employeeRecordArray[0]['blockId'] : $REQUEST_DATA['blockName'] );
									?>-->
									</select>
									</td>

									<td  align="right" style="padding-right:5px">
									<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="doSubmitAction();return false;" /></td>
								</tr>
								</table>
							  </td>
							</tr>
							<tr style="display:" id="showData">
								<td class="contenttab_row" valign="top" >
								<div id="results"></div>
								</td>
							</tr>

						</table>
					  </form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php floatingDiv_Start('ServiceDetails','Vehicle Service Details'); ?>
<form name="vehicleServiceRepairDetails" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="serviceRepairId" id="serviceRepairId" value="" />

		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Select Vehicle Type</b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" disabled="disabled">
			<option value="">Select</option>
			<?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getVehicleTypes();
			?>
			</select>
			</td>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No.</b></nobr></td>
			<td class="padding">:&nbsp;&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" disabled="disabled">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Type</b></nobr></td>
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
			<span id="serviceDate1"></span>
			</nobr></td>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;KM Reading on Entry</b></nobr></td>
			<td class="padding"><nobr><b>:&nbsp;</b>
				<span id="readingEntry"></span>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Bill/Ticked No.</b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
				<span id="billNo"></span></td>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Serviced at</b></nobr></td>
			<td class="padding"><nobr><b>:&nbsp;</b>
				<span id="servicedAt"></span>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Details</b></nobr></td>
			<td width="79%" class="padding"><nobr><b>:&nbsp;</b>
		</tr>
		<tr>
			<td colspan="6"><div id="serviceDetail1" style="height:200px;overflow:auto;"></div></td>
		</tr>
			<tr>
			<td colspan="6"><div id="vehicleServiceRepairDetail" style="height:100px;overflow:auto;"></div></td>
		</tr>
</table>
</form>
 <?php floatingDiv_End(); ?>


<?php
// $History: listVehicleReportContents.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/29/10    Time: 5:19p
//Updated in $/Leap/Source/Templates/Vehicle
//change the breadcrumb
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>