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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddTyreRetreading',330,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddTyreRetreading','Add New Entry For Tyre Retreading'); ?>
<form name="addTyreRetreading" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Tyre No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="tyreNo" name="tyreNo" class="inputbox" maxlength="50" onChange = "getHistoryTyreBus();"></td>
		</tr>
       
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Registration No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="contenttab_internal_rows"><b>&nbsp;:</b>&nbsp;<div id="busNo" style="display:inline" ></div>
			</td>
            <td>
              <span id="linkHistory" style="display:none">
                <a class='blueLinkSimple' href='#' onClick='return showHistoryDetails(); return false;'>Tyre history</a>
              </span>
          </td>
		</tr>
		<tr>    
			<td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Retreading Date</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('retreadingDate',date('Y-m-d'));
			?></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Meter Reading<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="reading" name="reading" class="inputbox" maxlength="9" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Reason</b></nobr></td>
			<td class="padding">:&nbsp;<textarea id="replacementReason" name="replacementReason" cols="22" rows="3" class="inputbox" style="vertical-align:top" maxlength="200" onkeyup="return ismaxlength(this)"></textarea></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddTyreRetreading');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditTyreRetreading','Edit Type Retreading'); ?>
<form name="editTyreRetreading" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="retreadingId" id="retreadingId" value="" />
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Tyre No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="70%" class="padding">:&nbsp;<input type="text" id="tyreNo" name="tyreNo" class="inputbox" maxlength="50" onChange = "getEditHistoryTyreBus();"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="contenttab_internal_rows"><b>&nbsp;:</b>&nbsp;<div id="editBusNo" style="display:inline" ></div>
			</td>
		</tr>
		<tr>    
			<td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Retreading Date</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('retreadingDate1',date('Y-m-d'));
			?></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Reading<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="reading" name="reading" class="inputbox" maxlength="9" ></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Reason</b></nobr></td>
			<td class="padding">:&nbsp;<textarea id="replacementReason" name="replacementReason" cols="22" rows="3" class="inputbox" style="vertical-align:top" maxlength="200" onkeyup="return ismaxlength(this)"></textarea></td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:18px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditTyreRetreading'); return false;" />
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
<?php  floatingDiv_Start('divHistoryInfo','Tyre History','12','','','1'); ?>  
<div id="historyInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;" name="divHistoryInfo"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>  
        <tr>    
            <td width="89%">     
                <div id="tyreHistory" style="vertical-align:top;" ></div> 
            </td>
        </tr>     
    </table>
</div>       
<?php floatingDiv_End(); ?>     
<?php 
// $History: listTyreRetreadingContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/05/10    Time: 11:03a
//Updated in $/Leap/Source/Templates/TyreRetreading
//fixed bug nos. 0002484, 0002427
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:42p
//Updated in $/Leap/Source/Templates/TyreRetreading
//fixed bug no.0002421
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/TyreRetreading
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/TyreRetreading
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:36p
//Created in $/Leap/Source/Templates/TyreRetreading
//new template file for tyre retreading
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