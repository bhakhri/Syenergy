<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>  <tr><td class="contenttab_internal_rows" height="20"><b>Note : </b>Only the latest Fuel entry can be edited or deleted. So please carefully add new entries. </td></tr>
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFuel',360,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddFuel','Add Fuel Uses'); ?>
<form name="AddFuel" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="100%" class="contenttab_internal_rows" style="padding-left:5px" colspan="3"><nobr>
         <div id="fuelSummaryDiv" style="display:inline;text-align:center">
         </div>
        </nobr></td>
    </tr>
    <tr>
		<td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
		<td class="padding"><b>:</b></td>
		<td width="79%" class="padding"><select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails()">
		<option value="">Select</option>
		<?php
		  require_once(BL_PATH.'/HtmlFunctions.inc.php');
		  echo HtmlFunctions::getInstance()->getVehicleTypes();
		?>
		</select>
		</td>
	</tr>
	<tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Vehicle Registration No.<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="busId" id="busId" onchange="getLastMilege(this.value);">
        <option value="">Select</option>
              <?php
                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1'); //only active busses
              ?>
        </select></nobr></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="staffId" id="staffId">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTransportStaffData('',"WHERE leavingDate = '' OR leavingDate = '0000-00-00' OR leavingDate IS NULL ");
              ?>
        </select></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Dated<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dated1',date('Y-m-d'),1);
              ?>
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Last Mileage<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="lastMilege" id="lastMilege" class="inputbox" maxlength="15" disabled="disabled" value="0" />
        </nobr>
    </td>
  </tr>
  <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Current Mileage<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="currentMilege" id="currentMilege" class="inputbox" maxlength="15" onkeydown="return sendKeys(1,'currentMilege',event);"  />
        </nobr>
    </td>
  </tr>
  <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Litres<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="litres" id="litres" class="inputbox" maxlength="15" style="width:70px;" onkeydown="return sendKeys(1,'litres',event);" onchange="getAmountCalculation();" />
        </nobr>
    </td>
  </tr>
  <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Rate</b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="rate" id="rate" class="inputbox" maxlength="10" style="width:70px;" onkeydown="return sendKeys(1,'rate',event);" onchange="getAmountCalculation();"/>
        </nobr>
    </td>
  </tr>
  <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="amount" id="amount" class="inputbox" maxlength="15" style="width:70px;" onkeydown="return sendKeys(1,'amount',event);" />&nbsp;(Rs.)
        </nobr>
    </td>
  </tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFuel');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditFuel','Edit Fuel Uses'); ?>
<form name="EditFuel" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="fuelId" id="fuelId" value="" />  
	<tr>
		<td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
		<td class="padding"><b>:</b></td>
		<td width="79%" class="padding"><select size="1" class="inputbox1" name="vehicleType" id="vehicleType" disabled="disabled">
		<option value="">Select</option>
		<?php
		  require_once(BL_PATH.'/HtmlFunctions.inc.php');
		  echo HtmlFunctions::getInstance()->getVehicleTypes();
		?>
		</select>
		</td>
	</tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Vehicle Registration No.<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="busId" id="busId" disabled="disabled">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1'); //only active busses
              ?>
        </select></nobr></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="staffId" id="staffId">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTransportStaffData('',"WHERE leavingDate = '' OR leavingDate = '0000-00-00' OR leavingDate IS NULL");
              ?>
        </select></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Dated<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dated2',date('Y-m-d'),2);
              ?>
        </nobr>
    </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Last Mileage<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="lastMilege" id="lastMilege" class="inputbox" maxlength="15" disabled="disabled" />
        </nobr>
    </td>
  </tr>
  <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Current Mileage<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="currentMilege" id="currentMilege" class="inputbox" maxlength="15" onkeydown="return sendKeys(2,'currentMilege',event);"  />
        </nobr>
    </td>
  </tr>
  <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Litres<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="litres" id="litres" class="inputbox" maxlength="15" style="width:70px;" onkeydown="return sendKeys(2,'litres',event);" />
        </nobr>
    </td>
  </tr>
   <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Rate</b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="rate" id="rate" class="inputbox" maxlength="10" style="width:70px;" onkeydown="return sendKeys(1,'rate',event);" onchange="getEditAmountCalculation();"/>
        </nobr>
    </td>
  </tr>
  <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">&nbsp;<b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" name="amount" id="amount" class="inputbox" maxlength="15" style="width:70px;" onkeydown="return sendKeys(2,'amount',event);" />&nbsp;(Rs.)
        </nobr>
    </td>
  </tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFuel');return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
<?php
// $History: listFuelContents.php $
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Templates/Fuel
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:33p
//Updated in $/Leap/Source/Templates/Fuel
//fixed bug nos. 0002370,0002369,0002365,0002363,0002362,0002361,0002368,
//0002366,0002360,0002359,0002372,0002358,0002357
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/Fuel
//fixed bug during self testing
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 12/18/09   Time: 6:28p
//Updated in $/Leap/Source/Templates/Fuel
//changes in fuel as database changed
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 17/09/09   Time: 18:11
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing found in self testing
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 3/08/09    Time: 15:46
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing.
//bug ids---
//0000817 to 0000821
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/06/09   Time: 11:15
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing.
//bug ids---0000063,0000082,0000083,0000085,0000087,0000090,0000092,
//0000095
//
//*****************  Version 4  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Templates/Fuel
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/04/09   Time: 12:47
//Updated in $/Leap/Source/Templates/Fuel
//Modified bus repair modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:03
//Created in $/Leap/Source/Templates/Fuel
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:50
//Updated in $/SnS/Templates/Fuel
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:37
//Created in $/SnS/Templates/Fuel
//Created Fuel Master
?>