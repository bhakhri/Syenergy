<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR VEHICLE TYRE LISTING 
//
// Author :Jaineesh
// Created on : (25.11.2009)
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
					<!--<form action="" method="" name="searchForm">
						<input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
						&nbsp;
						<input type="image" name="submit" align="absbottom" src="<?php echo IMG_HTTP_PATH;?>/search.gif" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" />
					</form>-->
					</td>
				</tr>
			</table>
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
									<td class="padding"><select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails()">
									<option value="">Select</option>
									<?php
									  require_once(BL_PATH.'/HtmlFunctions.inc.php');
									  echo HtmlFunctions::getInstance()->getVehicleTypes();
									?>
									</select>
									</td>
								
									<td class="contenttab_internal_rows"><nobr><b>Registration No. :</b></nobr></td>
									<td class="padding">&nbsp;<select size="1" class="selectfield" name="vehicleNo" id="vehicleNo" onchange="clearText1()" >
									<option value="">Select</option>
									<!--<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->getBlockData($REQUEST_DATA['blockName']==''? $employeeRecordArray[0]['blockId'] : $REQUEST_DATA['blockName'] );
									?>-->
									</select>
									</td>
																	 
									<td  align="right" style="padding-right:5px">
									<input type="hidden" name="listSubject" value="1">
									<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getVehicleTyre(); return false;"/></td>
								</tr>
								</table>
							  </td>
							</tr>
							<tr style="display:none" id="showTitle">
								<td class="contenttab_border" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr >
											<td class="content_title">Tyre Detail : </td>
											<!--<td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
											align="right" onClick="displayWindow('AddVehicleTyre',330,250);blankValues();return false;" />&nbsp;</td>-->
										</tr>
									</table>
								</td>
							</tr>
							<tr style="display:none" id="showData">
								<td class="contenttab_row" valign="top" ><div id="results"></div>
								</td>
							</tr>
							<tr style="display:none" id="showPrint"><td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();return false;" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td></tr>
						</table>
					  </form>
					</td>
				</tr>

			</table>
		</td>
	</tr>
</table>
<!--Start Add Div-->

<?php floatingDiv_Start('AddVehicleTyre','Add Vehicle Tyre'); ?>
<form name="addVehicleTyre" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<input type="hidden" name="addTyreId" id="addTyreId" value="" />
	<input type="hidden" name="addTyreType" id="addTyreType" value="" />
		<tr>
			<td colspan="3" height="20%">
				<div id = "showTyreNo" ></div>
			<td>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Tyre Number <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="tyreNumber" name="tyreNumber" class="inputbox" maxlength="50" onkeydown="return sendKeys(1,'tyreNumber',event);"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Manufacturer<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="manufacturer" name="manufacturer" class="inputbox" maxlength="50" onkeydown="return sendKeys(1,'manufacturer',event);"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Model No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="modelNumber" name="modelNumber" class="inputbox" onkeydown="return sendKeys(1,'modelNumber',event);"></td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No.</b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" disabled="disabled">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1'); //only active busses
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Reading<?php echo REQUIRED_FIELD; 
            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
            echo HtmlFunctions::getInstance()->getHelpLink('Reading',HELP_VEHICLE_READING);?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="busReading" name="busReading" class="inputbox" maxlength="9" onkeydown="return sendKeys(1,'busReading',event);"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Purchase Date</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('purchaseDate',date('Y-m-d'));
		?></td>
		</tr>
		<!--<tr>    
			<td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Active </b></nobr></td>
			<td  class="padding" align="left"><nobr>:&nbsp;
			 <input type="radio" id="isActive" name="isActive1" value="1" checked="true"/>Yes&nbsp;
			 <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;</nobr>
			</td>
		</tr>-->
		<tr>
			<td colspan="3">
				<div id = "showOldTyreNo" ></div>
			<td>
		<tr>
		<tr>    
			<td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Used as 
          <?php  require_once(BL_PATH.'/HtmlFunctions.inc.php');   
           echo HtmlFunctions::getInstance()->getHelpLink('Used as',HELP_VEHICLE_USED_AS);?></b></nobr></td>
			<td  class="padding" align="left"><nobr>:&nbsp;
			 <input type="radio" id="usedAsMainTyre" name="usedAsMainTyre1" value="1" checked="true"/>Damage&nbsp;
			 <input type="radio" id="usedAsMainTyre" name="usedAsMainTyre1" value="1" />To Stores&nbsp;</nobr>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Reason</b></nobr></td>
			<td class="padding">:&nbsp;<textarea id="placementReason" name="placementReason" cols="22" rows="3" class="inputbox" style="vertical-align:top"></textarea></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddVehicleTyre');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditVehicleTyre','Edit Vehicle Tyre'); ?>
<form name="editVehicleTyre" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="tyreId" id="tyreId" value="" />
		<input type="hidden" name="tyreType" id="tyreType" value="" />
		<input type="hidden" name="busId" id="busId" value="" />

		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Tyre Number <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="tyreNumber" name="tyreNumber" class="inputbox" maxlength="50" onkeydown="return sendKeys(2,'tyreNumber',event);" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Manufacturer<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="manufacturer" name="manufacturer" class="inputbox" maxlength="50" onkeydown="return sendKeys(2,'manufacturer',event);"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Model No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="modelNumber" name="modelNumber" class="inputbox" onkeydown="return sendKeys(2,'modelNumber',event);"></td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No.</b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" disabled="disabled" >
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Reading<?php echo REQUIRED_FIELD; 
            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
            echo HtmlFunctions::getInstance()->getHelpLink('Reading',HELP_VEHICLE_READING);?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="busReading" name="busReading" class="inputbox" maxlength="9" onkeydown="return sendKeys(2,'busReading',event);"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Purchase Date</b></nobr></td>
			<td class="padding">:&nbsp;<?php 
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('purchaseDate1',date('Y-m-d'));
				?>
			</td>
		</tr>
		<!--<tr>    
			<td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Active </b></nobr></td>
			<td  class="padding" align="left"><nobr>:&nbsp;
			 <input type="radio" id="isActive" name="isActive1" value="1" checked="true"/>Yes&nbsp;
			 <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;</nobr>
			</td>
		</tr>-->
		<tr>    
			<td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Used as 
            <?php  require_once(BL_PATH.'/HtmlFunctions.inc.php');   
           echo HtmlFunctions::getInstance()->getHelpLink('Used as',HELP_VEHICLE_EDIT_USED);?></b></nobr></td>
			<td  class="padding" align="left"><nobr>:&nbsp; 
			 <input type="radio" id="usedAsMainTyre" name="usedAsMainTyre1" value="1" checked="true" onclick="getMainTyreList()"/>Main&nbsp;
			 <input type="radio" id="usedAsMainTyre" name="usedAsMainTyre1" value="1" onclick="getSpareTyreList()"/>Spare&nbsp;</nobr>
			</td>
		</tr>
		<tr>
			<td width="30%"></td>
			<td><div id="showSpareDiv" style="display:none">&nbsp;&nbsp;Please select which tyre you want <br>&nbsp;&nbsp;to make <b>"Main Tyre"</b></div></td>
		</tr>
		<tr>
			<td width="30%"></td>
			<td><div id="showMainDiv" style="display:none">&nbsp;&nbsp;Please select which tyre you want <br>&nbsp;&nbsp;to make <b>"Spare Tyre"</b></div></td>
		</tr>
		<tr id="selectSpareTyre" style="display:none">
			<td width="30%"></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="usedSpareTyre" id="usedSpareTyre">
				<option value="">Select</option>
				<?php
					//require_once(BL_PATH.'/HtmlFunctions.inc.php');
					//echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr id="selectMainTyre" style="display:none">
			<!--<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Used as Main Tyre <?php echo REQUIRED_FIELD; ?></b></nobr></td>-->
			<td width="30%"></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="usedMainTyre" id="usedMainTyre" >
				<option value="">Select</option>
				<?php
					//require_once(BL_PATH.'/HtmlFunctions.inc.php');
					//echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Reason</b></nobr></td>
			<td class="padding">:&nbsp;<textarea id="placementReason" name="placementReason" cols="22" rows="3" class="inputbox" style="vertical-align:top"></textarea></td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
				<td align="center" style="padding-right:18px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditVehicleTyre');clearText(); return false;" />
				</td>
			</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->

<!--Start Extra Tyre Div-->
<?php floatingDiv_Start('AddStockVehicleTyre','Add Stock Vehicle Tyre'); ?>
<form name="addStockVehicleTyre" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<input type="hidden" name="addStockTyreId" id="addStockTyreId" value="" />
	<input type="hidden" name="addStockTyreType" id="addStockTyreType" value="" />
		<tr>
			<td colspan="3" height="20%">
				<div id = "showStockTyreNo" ></div>
			<td>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Stock Tyres<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="stockTyreNo" id="stockTyreNo" >
			<option value="">Select</option>
			<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getExtraTyres()
				?></select>
			</td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No.</b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="stockRegnNo" id="stockRegnNo" disabled="disabled">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1'); //only active busses
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Reading<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="stockVehicleReading" name="stockVehicleReading" class="inputbox" maxlength="9" onkeydown="return sendKeys(1,'busReading',event);"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Replacement Date</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('replacementDate',date('Y-m-d'));
		?></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Replacement Reason</b></nobr></td>
			<td class="padding">:&nbsp;<textarea id="replacementReason" name="replacementReason" cols="22" rows="3" class="inputbox" style="vertical-align:top"></textarea></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateStockAdddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddStockVehicleTyre');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
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
// $History: listVehicleTyreContents.php $
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 2/04/10    Time: 6:27p
//Updated in $/Leap/Source/Templates/VehicleTyre
//fixed issues
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/25/10    Time: 11:14a
//Updated in $/Leap/Source/Templates/VehicleTyre
//Show latest vehicle insurance detail non-editable
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 1/13/10    Time: 11:00a
//Updated in $/Leap/Source/Templates/VehicleTyre
//fixed bug in fleet management
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Templates/VehicleTyre
//fixed bug in fleet management
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:51p
//Updated in $/Leap/Source/Templates/VehicleTyre
//bug fixed for fleet management
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/07/10    Time: 2:44p
//Updated in $/Leap/Source/Templates/VehicleTyre
//fixed bug for fleet management
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Templates/VehicleTyre
//fixed bug in fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/VehicleTyre
//fixed bug on fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/VehicleTyre
//fixed bug during self testing
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/01/09   Time: 6:59p
//Updated in $/Leap/Source/Templates/VehicleTyre
//changes in interface of vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/25/09   Time: 3:31p
//Created in $/Leap/Source/Templates/VehicleTyre
//new template for vehicle tyre
//
//
?>